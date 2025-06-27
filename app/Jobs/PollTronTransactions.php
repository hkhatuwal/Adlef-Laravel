<?php

namespace App\Jobs;

use App\Models\AssetTransfer;
use App\Models\CryptoWallet;
use App\Models\PaymentTransaction;
use App\Models\Setting;
use App\Services\AssetTransferVerificationService;
use App\Services\PaymentGateway\TronGridPaymentGateway;
use App\Services\TronService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class PollTronTransactions implements ShouldQueue
{
    use Queueable;

    private const LAST_POLL_SETTING_KEY = 'tron_last_poll_timestamp';
    private const LAST_POLL_PAYMENT_GATEWAY_SETTING_KEY = 'tron_last_poll_payment_gateway_timestamp';

    private AssetTransferVerificationService $verificationService;
    private TronService $tronService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->verificationService = new AssetTransferVerificationService();
        $this->tronService = new TronService();
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->pollTronTransferInTransactions();
            $this->pollTrongridPaymentGatewayTransactions();
            Log::info('TRON transactions polling completed successfully at ' . Carbon::now()->toDateTimeLocalString());
        } catch (\Exception $e) {
            Log::error("Error polling TRON transactions: " . $e->getMessage());
        }
    }

    /**
     * Poll TRON API for new transactions
     * @throws \Exception
     */
    protected function pollTronTransferInTransactions(): void
    {
        try {
            // Get the last polling timestamp
            $lastPollTimestamp = Setting::get(self::LAST_POLL_SETTING_KEY);

            if (!$lastPollTimestamp) {
                $lastPollTimestamp = Carbon::now()->subDays(5)->timestamp * 1000; // Convert to milliseconds
            }

            Log::info("Polling TRON transactions from timestamp: " . $lastPollTimestamp);

            // Get all wallet addresses to poll
            $walletAddresses = $this->tronService->getWalletAddresses();
            $currentTimestamp = Carbon::now()->timestamp * 1000; // Convert to milliseconds
            $totalTransactionsFound = 0;

            // Poll each wallet address
            foreach ($walletAddresses as $walletAddress) {
                Log::info("Polling transactions for wallet: " . $walletAddress);

                $response = $this->tronService->fetchTransactions($walletAddress, $lastPollTimestamp);

                if ($response && isset($response['success']) && $response['success']) {
                    $transactions = $response['data'] ?? [];
                    $transactionCount = count($transactions);
                    $totalTransactionsFound += $transactionCount;

                    Log::info("Found " . $transactionCount . " transactions for wallet " . $walletAddress);

                    // Loop through transactions
                    foreach ($transactions as $transaction) {
                        try {
                            $this->processTransaction($transaction);
                        } catch (Throwable $e) {
                            Log::error('Failed to process transaction: ' . $e->getMessage());
                        }
                    }
                } else {
                    Log::error('Failed to fetch transactions from TRON API for wallet: ' . $walletAddress);
                }
            }

            // Update the last poll timestamp to current time after processing all wallets
            Setting::set(self::LAST_POLL_SETTING_KEY, $currentTimestamp);
            Log::info("Updated last poll timestamp to: " . $currentTimestamp);
            Log::info("Total transactions found across all wallets: " . $totalTransactionsFound);
        } catch (\Exception $e) {
            Log::error('Error in pollTronTransactions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    protected function pollTrongridPaymentGatewayTransactions(): void
    {
        try {
            // Get the last polling timestamp
            $lastPollTimestamp = Setting::get(self::LAST_POLL_PAYMENT_GATEWAY_SETTING_KEY);

            if (!$lastPollTimestamp) {
                $lastPollTimestamp = Carbon::now()->subDays(5)->timestamp * 1000; // Convert to milliseconds
            }

            Log::info("Polling TRON transactions from timestamp: " . $lastPollTimestamp);

            $paymentTransactions = PaymentTransaction::query()->where('gateway_name', 'trongrid');
            $paymentTransactions = $paymentTransactions->whereNotNull('gateway_transaction_id');
            $paymentTransactions = $paymentTransactions->where('status', 'pending')->where('updated_at', '>', Carbon::now()->subMinutes(30))->get();
            $currentTimestamp = Carbon::now()->timestamp * 1000; // Convert to milliseconds
            $totalTransactionsFound = 0;
            Log::info("Polling transactions for wallet: ");
            Log::info($paymentTransactions);

            // Poll each wallet address
            foreach ($paymentTransactions as $paymentTransaction) {
                $walletAddress = $paymentTransaction->gateway_transaction_id;
                $response = $this->tronService->fetchTransactions($walletAddress, $lastPollTimestamp);
                if ($response && isset($response['success']) && $response['success']) {
                    $transactions = $response['data'] ?? [];
                    $transactionCount = count($transactions);
                    $totalTransactionsFound += $transactionCount;

                    Log::info("Found " . $transactionCount . " transactions for wallet " . $walletAddress);

                    // Loop through transactions
                    foreach ($transactions as $transaction) {
                        try {
                            $this->processPaymentGatewayTransaction($paymentTransaction, $transaction);
                        } catch (Throwable $e) {
                            Log::error('Failed to process transaction: ' . $e->getMessage());
                        }
                    }
                } else {
                    Log::error('Failed to fetch transactions from TRON API for wallet: ' . $walletAddress);
                }
            }

            // Update the last poll timestamp to current time after processing all wallets
            Setting::set(self::LAST_POLL_PAYMENT_GATEWAY_SETTING_KEY, $currentTimestamp);
            Log::info("Updated last poll for payment gateway  timestamp to: " . $currentTimestamp);
            Log::info("Total transactions found across all wallets: " . $totalTransactionsFound);
        } catch (\Exception $e) {
            Log::error('Error in pollTronTransactions: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Process individual transaction
     * @throws Throwable
     */
    private function processTransaction(array $transaction): void
    {
        Log::info("Processing transaction: " . $transaction['transaction_id']);

        $transfers = AssetTransfer::query()->where('from_account_type', CryptoWallet::class);

        $transfers = $transfers->whereHasMorph(
            'from_account',
            [CryptoWallet::class],
            function ($query) use ($transaction) {
                $query->where('wallet_address', $transaction['from']);
            }
        )->whereHas('currency', function ($query) use ($transaction) {
            $query->where('symbol', $transaction['token_info']['symbol']);
        })->where('status', AssetTransfer::STATUS_PENDING)->get();


        if (empty($transfers)) {
            Log::info("No transfers found for transaction: " . $transaction['transaction_id']);
            return;
        }

        foreach ($transfers as $transfer) {
            $decimals = $transaction['token_info']['decimals'];
            $amount = $transaction['value'] / pow(10, $decimals);

            $this->verificationService->verifyTransferDetails($transfer, $amount, $transaction['token_info']['symbol'], .1);
            $this->verificationService->markTransferVerifiedAndNotifyUser($transfer);
            Log::info("Transaction processed: " . json_encode([
                    'id' => $transaction['transaction_id'],
                    'symbol' => $transaction['token_info']['symbol'],
                    'from' => $transaction['from'],
                    'to' => $transaction['to'],
                    'value' => $transaction['value'],
                    'timestamp' => $transaction['block_timestamp']
                ]));
            break;
        }

    }


    /**
     * Process individual transaction
     * @throws Throwable
     */
    private function processPaymentGatewayTransaction(PaymentTransaction $paymentTransaction, array $transaction): void
    {
        $decimals = $transaction['token_info']['decimals'];
        $amount = $transaction['value'] / pow(10, $decimals);


        if (abs(round($amount, 2) - round($paymentTransaction->amount, 2)) <= 0.1) {
            $paymentTransaction->update([
                'status' => PaymentTransaction::STATUS_COMPLETED,
            ]);
            Log::info("Transaction processed: " . json_encode([
                    "id" => $paymentTransaction->id,
                    "amount" => $amount,
                ]));
        } else {
            Log::error("Transaction failed to process : amount mismatch ");
        }


    }


}
