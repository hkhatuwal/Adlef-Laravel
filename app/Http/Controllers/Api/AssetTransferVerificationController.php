<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\AssetTransferController;
use App\Http\Controllers\Controller;
use App\Models\AssetTransfer;
use App\Services\NotificationService;
use App\Services\AssetTransferVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class AssetTransferVerificationController extends Controller
{
    protected $assetTransferController;
    protected $notificationService;
    protected $verificationService;

    public function __construct(
        AssetTransferController          $assetTransferController,
        NotificationService              $notificationService,
        AssetTransferVerificationService $verificationService
    )
    {
        $this->assetTransferController = $assetTransferController;
        $this->notificationService = $notificationService;
        $this->verificationService = $verificationService;
    }

    /**
     * Verify a transfer by reference ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyByReference(Request $request)
    {
        try {
            // Find and verify the transfer using the service
            $transfer = $this->verificationService->findTransferByReference($request->reference_id);

            $this->verificationService->validateRequestSignature($request);
            $receivedAmount = $request->amount / 100;          // THE AMOUNT IS IN CENTS
            $this->verificationService->verifyTransferDetails($transfer, $receivedAmount, strtoupper($request->currency));

            // Complete verification
            $this->verificationService->markTransferVerifiedAndNotifyUser($transfer);

            return $this->successResponse($transfer);

        } catch (Exception|\Throwable $e) {
            return $this->handleException($e, $request->reference_id ?? null, $request->all());
        }
    }

    /**
     * Verify a transfer by wallet address
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyByWalletAddress(Request $request)
    {
        try {
            // Validate wallet address request
            $validator = Validator::make($request->all(), [
                'wallet_address' => 'required|string',
                'signature' => 'required|string',
                'timestamp' => 'required|integer',
            ]);

            if ($validator->fails()) {
                throw new Exception('Validation failed: ' . json_encode($validator->errors()), 422);
            }

            // Find and verify the transfer using the service
            $transfer = $this->verificationService->findTransferByWalletAddress($request->wallet_address);
            $this->verificationService->verifyTransferDetails($transfer, $request);

            // Complete verification
            $this->assetTransferController->verifyTransferInApi($transfer, $request);

            return $this->successResponse($transfer);

        } catch (Exception $e) {
            return $this->handleException($e, $request->wallet_address ?? null, $request->all());
        }
    }


    /**
     * Create success response
     *
     * @param AssetTransfer $transfer
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse(AssetTransfer $transfer)
    {
        return response()->json([
            'success' => true,
            'message' => 'Transfer verified successfully',
            'data' => [
                'reference_id' => $transfer->reference_number,
                'status' => 'verified',
                'verified_at' => now()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Handle all exceptions and send notifications
     *
     * @param Exception $e
     * @param string|null $referenceId
     * @param array $requestData
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleException(Exception $e, $referenceId, array $requestData = [])
    {
        $code = $e->getCode() ?: 500;
        $message = $e->getMessage();

        // Log the error
        Log::error('Error verifying transfer: ' . $message, [
            'reference_id' => $referenceId,
            'trace' => $e->getTraceAsString(),
            'request_data' => $requestData
        ]);

        // Try to find the transfer for notification
        $transfer = null;
        if ($referenceId) {
            $transfer = AssetTransfer::where('reference_number', $referenceId)->first();
        }

        // Send notification if we have a transfer
        if ($transfer && $transfer->user) {
            $this->sendFailureNotification($transfer, $message);
        }

        return response()->json([
            'success' => false,
            'message' => $code == 500 ? 'Failed to verify transfer' : $message,
            'error' => config('app.debug') && $code == 500 ? $message : null
        ], $code < 100 || $code > 599 ? 500 : $code);
    }

    /**
     * Send failure notification to user
     *
     * @param AssetTransfer $transfer
     * @param string $errorMessage
     * @return void
     */
    private function sendFailureNotification(AssetTransfer $transfer, string $errorMessage)
    {
        $this->notificationService->notify(
            $transfer->user,
            [
                'type' => 'warning',
                'title' => 'Transfer Verification Failed',
                'message' => "There was an issue verifying your transfer: {$errorMessage}. Please contact support.",
                'notifiable_type' => AssetTransfer::NOTIFICATION_TRANSFER_FAILED,
                'notifiable_id' => $transfer->id,
                'metadata' => [
                    'reference_number' => $transfer->reference_number,
                    'amount' => $transfer->amount,
                    'currency' => $transfer->currency->symbol ?? 'Unknown',
                    'fee' => $transfer->fee,
                    'failed_at' => now()->format('Y-m-d H:i:s'),
                    'reason' => $errorMessage
                ]
            ],
            ['database', 'email']
        );
    }


}
