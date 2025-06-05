<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\AssetTransferController;
use App\Models\AssetTransfer;
use App\Models\CryptoWallet;
use App\Models\Setting;
use App\Services\AssetTransferVerificationService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class PollTronTransactions implements ShouldQueue
{
    use Queueable;

    private const TRON_WALLET_ADDRESS = 'TETRZNcZwB4RAneyBytqm6KFdMuC6Ug47C';
    private const TRON_API_KEY = 'a840beed-cc41-45de-9e58-c538671ed053';
    private const LAST_POLL_SETTING_KEY = 'tron_last_poll_timestamp';

    private AssetTransferVerificationService $verificationService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->verificationService = new AssetTransferVerificationService();
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->pollTronTransactions();
            Log::info('TRON transactions polling completed successfully at ' . Carbon::now()->toDateTimeLocalString());
        } catch (\Exception $e) {
            Log::error("Error polling TRON transactions: " . $e->getMessage());
        }
    }

    /**
     * Poll TRON API for new transactions
     * @throws \Exception
     */
    protected function pollTronTransactions(): void
    {
        try {
            // Get the last polling timestamp
            $lastPollTimestamp = Setting::get(self::LAST_POLL_SETTING_KEY);

            // If no previous poll, start from 2 minutes ago
            if (!$lastPollTimestamp) {
                $lastPollTimestamp = Carbon::now()->subDays(5)->timestamp * 1000; // Convert to milliseconds
            }

            Log::info("Polling TRON transactions from timestamp: " . $lastPollTimestamp);

            // Make cURL request to TRON API
            $response = $this->makeTronApiRequest($lastPollTimestamp);

            if ($response && isset($response['success']) && $response['success']) {
                $transactions = $response['data'] ?? [];
                Log::info("Found " . count($transactions) . " transactions to process");
                // Loop through transactions
                foreach ($transactions as $transaction) {
                    try {
                        $this->processTransaction($transaction);
                    } catch (Throwable $e) {
                        Log::error('Failed To Process The transaction: ' . $e->getMessage());
                    }
                }

                // Update the last poll timestamp to current time
                $currentTimestamp = Carbon::now()->timestamp * 1000; // Convert to milliseconds
                Setting::set(self::LAST_POLL_SETTING_KEY, $currentTimestamp);

                Log::info("Updated last poll timestamp to: " . $currentTimestamp);
            } else {
                Log::error('Failed to fetch transactions from TRON API or invalid response format');
            }
        } catch (\Exception $e) {
            Log::error('Error in pollTronTransactions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Make cURL request to TRON API
     */
    private function makeTronApiRequest($minTimestamp): ?array
    {
        $curl = curl_init();

        $url = 'https://api.trongrid.io/v1/accounts/' . self::TRON_WALLET_ADDRESS . '/transactions/trc20?min_timestamp=' . $minTimestamp;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'TRON-PRO-API-KEY: ' . self::TRON_API_KEY
            ),
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            Log::error("cURL error: " . $error);
            return null;
        }

        curl_close($curl);

        if ($httpCode !== 200) {
            Log::error("HTTP error: " . $httpCode . ", Response: " . $response);
            return null;
        }

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON decode error: " . json_last_error_msg());
            return null;
        }

        return $decodedResponse;
    }

    /**
     * Process individual transaction
     * @throws Throwable
     */
    private function processTransaction(array $transaction): void
    {
        Log::info("Processing transaction: " . $transaction['transaction_id']);

        $transfers = AssetTransfer::query()->where('from_account_type', CryptoWallet::class);
        $transfers = $transfers->wherehas('from_account', function ($query) use ($transaction) {
            $query->where('wallet_address', $transaction['from']);
        })->whereHas('currency', function ($query) use ($transaction) {
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


}
