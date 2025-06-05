<?php

namespace App\Services;

use App\Models\AssetTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class AssetTransferVerificationService
{
private NotificationService $notificationService;
    public function __construct()
    {
        $this->notificationService = new NotificationService();

    }


    /**
     * Verify transfer details (amount and currency)
     *
     * @param AssetTransfer $transfer
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function verifyTransferDetails(AssetTransfer $transfer, $receivedAmount, $receivedCurrency,$amountPercentageDiff=1): void
    {
        $this->checkAmountMatch($transfer, $receivedAmount,$amountPercentageDiff);
        $this->checkCurrencyMatch($transfer, $receivedCurrency);
    }

    /**
     * Validate request signature and timestamp
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function validateRequestSignature(Request $request): array
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'reference_id' => 'required|string',
            'signature' => 'required|string',
            'timestamp' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new Exception('Validation failed: ' . json_encode($validator->errors()), 422);
        }

        // Verify the request signature
        if (!$this->verifySignature($request)) {
            Log::warning('Invalid signature for transfer verification', [
                'reference_id' => $request->reference_id,
                'ip' => $request->ip()
            ]);

            throw new Exception('Invalid signature', 401);
        }

        // Check if the timestamp is within acceptable range (5 minutes)
        if (time() - $request->timestamp > 300) {
            throw new Exception('Request expired', 401);
        }

        return $validator->validated();
    }

    /**
     * Find the transfer by reference number
     *
     * @param string $referenceId
     * @return AssetTransfer
     * @throws Exception
     */
    public function findTransferByReference(string $referenceId): AssetTransfer
    {
        $transfer = AssetTransfer::where('reference_number', $referenceId)
            ->where('status', 'pending')
            ->where('transfer_type', AssetTransfer::TYPE_IN)
            ->first();

        if (!$transfer) {
            throw new Exception('Transfer not found or not in pending state', 404);
        }

        return $transfer;
    }


    /**
     * Check if amount matches
     *
     * @param AssetTransfer $transfer
     * @return void
     * @throws Exception
     */
    private function checkAmountMatch(AssetTransfer $transfer, $receivedAmount,$amountPercentageDiff): void
    {
        $expectedAmount = $transfer->amount;
        $percentageDiff = 100 - ($receivedAmount / $expectedAmount) * 100;

        if ($percentageDiff > $amountPercentageDiff) {
            throw new Exception('Amount mismatch: Received ' . $receivedAmount . ' but expected ' . $expectedAmount, 422);
        }
    }

    /**
     * Check if currency matches
     *
     * @param AssetTransfer $transfer
     * @param Request $request
     * @return void
     * @throws Exception
     */
    private function checkCurrencyMatch(AssetTransfer $transfer, $receivedCurrency): void
    {
        $expectedCurrency = strtoupper($transfer->currency->symbol);
        if ($receivedCurrency !== $expectedCurrency) {
            throw new Exception(
                "Currency mismatch: Received {$receivedCurrency} but expected {$expectedCurrency}",
                422
            );
        }
    }

    /**
     * Verify the request signature
     *
     * @param Request $request
     * @return bool
     */
    private function verifySignature(Request $request): bool
    {
        // Get the API secret from config
        $apiSecret = config('services.transfer_verification.secret');

        // Data to sign: reference_id + timestamp + secret
        $dataToSign = $request->reference_id . $request->timestamp . $apiSecret;

        // Generate expected signature
        $expectedSignature = hash_hmac('sha256', $dataToSign, $apiSecret);

        // Compare with provided signature (constant time comparison)
        return hash_equals($expectedSignature, $request->signature);
    }


    /**
     * @throws \Throwable
     */
    public function markTransferVerifiedAndNotifyUser(AssetTransfer $transfer): void
    {
        DB::beginTransaction();
        try {

// Update transfer status
            $transfer->status = 'completed';
            $transfer->save();

            // Update the user's account balance
            $userAccount = $transfer->to_account;
            $userAccount->balance += $transfer->amount;
            $userAccount->save();


            // Create notification
            $this->notificationService->notify(
                $transfer->user,
                [
                    'type' => 'success',
                    'title' => 'Transfer Verified',
                    'message' => "Your deposit of {$transfer->amount} {$transfer->currency->code} has been verified.",
                    'notifiable_type' => AssetTransfer::NOTIFICATION_TRANSFER_IN_SUCCESS,
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'reference_number' => $transfer->reference_number,
                        'amount' => $transfer->amount,
                        'currency' => $transfer->currency->symbol,
                        'fee' => $transfer->fee,
                        'verified_at' => now()->format('Y-m-d H:i:s'),
                        'verified_by' => 'Admin'
                    ]
                ],
                ['database', 'email']
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;

        }

    }
}
