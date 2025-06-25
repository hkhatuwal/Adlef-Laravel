<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetTransfer;
use App\Models\TransferOutPayment;
use App\Services\NotificationService;
use App\Services\TronService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssetTransferController extends Controller
{

    public function __construct(
        private NotificationService $notificationService,
        private TronService $tronService
    ){

    }
    public function index()
    {
        $transfers = AssetTransfer::with([
            'user',
            'currency',
            'from_account',
            'to_account'
        ])->latest()->paginate(20);

        return view('admin.transfers.index', compact('transfers'));
    }

    public function show(AssetTransfer $transfer)
    {
        $transfer->load([
            'user',
            'currency',
            'from_account',
            'to_account',
            'activities',
            'latestTransferOutPayment'
        ]);

        return view('admin.transfers.show', compact('transfer'));
    }

    public function verify(AssetTransfer $transfer, Request $request)
    {

        try {
            if ($transfer->status !== 'pending') {
                return back()->with('error', 'This transfer cannot be verified because it is not in pending state.');
            }

            if ($transfer->transfer_type==AssetTransfer::TYPE_OUT){
                $this->verifyTransferOut($transfer,$request);
            }
            else{
                $this->verifyTransferIn($transfer,$request);
            }

            return back()->with('success', 'Transfer has been verified successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to verify transfer: ' . $e->getMessage());
        }
    }


    private function verifyTransferOut(AssetTransfer $transfer, Request $request){
        // Get the fee percentage from request or use default
        $feePercentage = $request->input('fee_percentage', 0);

        // Calculate new fee
        $feeCalculator = new \App\Utils\FeeCalculator();
        $oldFee=$transfer->fee;
        $newFee = $feeCalculator->calculateTransferFee($transfer->amount, $feePercentage);
        $feeDifference=$newFee-$oldFee;
        if ($feeDifference>0){
            $transfer->from_account->update([
                'balance' => $transfer->from_account->balance - $feeDifference
            ]);
        }


        // Update transfer with new fee and status
        $transfer->update([
            'fee' => $newFee,
            'transaction_cost' => $request->transaction_cost_value,
            'status' => 'completed'
        ]);

        $this->notificationService->notify(
            $transfer->user,
            [
                'type' => 'success',
                'title' => 'Transfer Out Verified',
                'message' => "Your transfer ({$transfer->reference_number}) has been verified successfully.",
                'notifiable_type' => AssetTransfer::NOTIFICATION_TRANSFER_SUCCESS,
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
    }
    private function verifyTransferIn(AssetTransfer $transfer, Request $request){

        $transfer->to_account->update([
            'balance' => $transfer->amount
        ]);
        // Update transfer with new fee and status
        $transfer->update([
            'status' => 'completed'
        ]);

        $this->notificationService->notify(
            $transfer->user,
            [
                'type' => 'success',
                'title' => 'Transfer Verified',
                'message' => "Your transfer ({$transfer->reference_number}) has been verified successfully.",
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
    }



    /**
     * Create a new payment record for outgoing transfer
     */
    public function createPayment(AssetTransfer $transfer)
    {
        try {
            if ($transfer->transfer_type !== AssetTransfer::TYPE_OUT) {
                return back()->with('error', 'Payment records can only be created for outgoing transfers.');
            }

            if ($transfer->latestTransferOutPayment()->where('payment_status', 'pending')->exists()) {
                return back()->with('error', 'A payment record already exists for this transfer.');
            }

            // Load necessary relationships
            $transfer->load(['currency', 'to_account']);

            // Check if this is a crypto transfer (TRON/USDT)
            if ($transfer->currency->type === 'crypto' && in_array(strtoupper($transfer->currency->symbol), ['USDT', 'TRX'])) {
                return $this->createCryptoPayment($transfer);
            } else {
                // For non-crypto transfers, just create a pending payment record
                return $this->createRegularPayment($transfer);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create payment record: ' . $e->getMessage());
        }
    }

    /**
     * Create payment for crypto transfers using TronService
     */
    private function createCryptoPayment(AssetTransfer $transfer)
    {
        try {
            // Get the destination wallet address
            $toAddress = $transfer->to_account->wallet_address;

            if (!$toAddress) {
                return back()->with('error', 'Invalid destination wallet address.');
            }

            // Create the transfer using TronService
            $tronResponse = $this->tronService->createTransfer($toAddress, $transfer->amount);

            if (!$tronResponse || !isset($tronResponse['success']) || !$tronResponse['success']) {
                // Create failed payment record
                $payment = $transfer->createTransferOutPayment();
                $payment->markAsFailed('Failed to initiate TRON transfer: ' . ($tronResponse['message'] ?? 'Unknown error'));

                return back()->with('error', 'Failed to initiate crypto transfer: ' . ($tronResponse['message'] ?? 'Unknown error'));
            }

            // Extract transaction details from successful response
            $txData = $tronResponse['data'];
            $txid = $txData['txid'];
            $actualAmount = $txData['amount'];
            $fromAddress = $txData['fromAddress'];

            // Create payment record with transaction details
            $payment = $transfer->createTransferOutPayment($txid);

            // Mark as sent immediately since TRON transfer was successful
            $payment->markAsSent($txid);

            // Store additional metadata
            $payment->update([
                'payment_metadata' => [
                    'tron_response' => $tronResponse,
                    'from_address' => $fromAddress,
                    'to_address' => $toAddress,
                    'actual_amount' => $actualAmount,
                    'currency' => $transfer->currency->symbol,
                    'network' => 'TRON'
                ]
            ]);

            $this->notificationService->notify(
                $transfer->user,
                [
                    'type' => 'success',
                    'title' => 'Crypto Payment Sent',
                    'message' => "Your crypto transfer of {$actualAmount} {$transfer->currency->symbol} has been sent successfully. Transaction ID: {$txid}",
                    'notifiable_type' => AssetTransfer::class,
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'reference_number' => $transfer->reference_number,
                        'transaction_id' => $payment->transaction_id,
                        'tron_txid' => $txid,
                        'amount' => $actualAmount,
                        'currency' => $transfer->currency->symbol,
                        'to_address' => $toAddress,
                        'sent_at' => now()->format('Y-m-d H:i:s'),
                        'sent_by' => 'Admin'
                    ]
                ],
                ['database', 'email']
            );

            return back()->with('success', "Crypto transfer sent successfully! TRON Transaction ID: {$txid}");

        } catch (\Exception $e) {
            // Create failed payment record if something goes wrong
            try {
                $payment = $transfer->createTransferOutPayment();
                $payment->markAsFailed('Exception during crypto transfer: ' . $e->getMessage());
            } catch (\Exception $innerE) {
                // Log the inner exception but don't interfere with the main error
                Log::error('Failed to create failed payment record', ['error' => $innerE->getMessage()]);
            }

            return back()->with('error', 'Failed to create crypto payment: ' . $e->getMessage());
        }
    }

    /**
     * Create regular payment record for non-crypto transfers
     */
    private function createRegularPayment(AssetTransfer $transfer)
    {
        $payment = $transfer->createTransferOutPayment();

        $this->notificationService->notify(
            $transfer->user,
            [
                'type' => 'info',
                'title' => 'Payment Processing Started',
                'message' => "Payment processing has been initiated for your transfer ({$transfer->reference_number}).",
                'notifiable_type' => AssetTransfer::class,
                'notifiable_id' => $transfer->id,
                'metadata' => [
                    'reference_number' => $transfer->reference_number,
                    'transaction_id' => $payment->transaction_id,
                    'initiated_at' => now()->format('Y-m-d H:i:s'),
                    'initiated_by' => 'Admin'
                ]
            ],
            ['database']
        );

        return back()->with('success', 'Payment record created successfully. Transaction ID: ' . $payment->transaction_id);
    }

    /**
     * Mark payment as sent
     */
    public function markPaymentSent(AssetTransfer $transfer, Request $request)
    {
        try {
            $latestPayment = $transfer->latestTransferOutPayment;

            if (!$latestPayment) {
                return back()->with('error', 'No payment record found for this transfer.');
            }

            if (!$latestPayment->isPending()) {
                return back()->with('error', 'Payment can only be marked as sent if it is currently pending.');
            }

            $paymentReference = $request->input('payment_reference', 'ADMIN-SENT-' . now()->timestamp);
            $latestPayment->markAsSent($paymentReference);

            $this->notificationService->notify(
                $transfer->user,
                [
                    'type' => 'info',
                    'title' => 'Payment Sent',
                    'message' => "Your payment for transfer ({$transfer->reference_number}) has been sent and is being processed.",
                    'notifiable_type' => AssetTransfer::class,
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'reference_number' => $transfer->reference_number,
                        'transaction_id' => $latestPayment->transaction_id,
                        'payment_reference' => $paymentReference,
                        'sent_at' => now()->format('Y-m-d H:i:s'),
                        'sent_by' => 'Admin'
                    ]
                ],
                ['database']
            );

            return back()->with('success', 'Payment marked as sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to mark payment as sent: ' . $e->getMessage());
        }
    }

    /**
     * Confirm payment completion
     */
    public function confirmPayment(AssetTransfer $transfer)
    {
        try {
            $latestPayment = $transfer->latestTransferOutPayment;

            if (!$latestPayment) {
                return back()->with('error', 'No payment record found for this transfer.');
            }

            if (!$latestPayment->isSent()) {
                return back()->with('error', 'Payment can only be confirmed if it has been marked as sent.');
            }

            $latestPayment->markAsConfirmed();

            return back()->with('success', 'Payment confirmed successfully. You can Mark the transfer as verified now.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to confirm payment: ' . $e->getMessage());
        }
    }

    /**
     * Retry failed payment
     */
    public function retryPayment(AssetTransfer $transfer)
    {
        try {
            $latestPayment = $transfer->latestTransferOutPayment;

            if (!$latestPayment) {
                return back()->with('error', 'No payment record found for this transfer.');
            }

            if (!$latestPayment->isFailed()) {
                return back()->with('error', 'Only failed payments can be retried.');
            }

            // Create a new payment record for retry
            $newPayment = $transfer->createTransferOutPayment();

            $this->notificationService->notify(
                $transfer->user,
                [
                    'type' => 'info',
                    'title' => 'Payment Retry Initiated',
                    'message' => "A new payment attempt has been initiated for your transfer ({$transfer->reference_number}).",
                    'notifiable_type' => AssetTransfer::class,
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'reference_number' => $transfer->reference_number,
                        'new_transaction_id' => $newPayment->transaction_id,
                        'previous_transaction_id' => $latestPayment->transaction_id,
                        'retry_initiated_at' => now()->format('Y-m-d H:i:s'),
                        'retry_initiated_by' => 'Admin'
                    ]
                ],
                ['database']
            );

            return back()->with('success', 'Payment retry initiated successfully. New Transaction ID: ' . $newPayment->transaction_id);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retry payment: ' . $e->getMessage());
        }
    }

    public function reject(AssetTransfer $transfer)
    {
        try {
            if ($transfer->status !== 'pending') {
                return back()->with('error', 'This transfer cannot be rejected because it is not in processing state.');
            }

            $transfer->update(['status' => 'failed']);
            $this->notificationService->notify(
                $transfer->user,
                [
                    'type' => 'error',
                    'title' => 'Transfer Cancelled',
                    'message' => "Your deposit of {$transfer->amount} {$transfer->currency->code} has been cancelled.",
                    'notifiable_type' => AssetTransfer::NOTIFICATION_TRANSFER_IN_FAILED,
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


            return back()->with('success', 'Transfer has been rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject transfer: ' . $e->getMessage());
        }
    }

    public function hold(AssetTransfer $transfer)
    {
        try {
            if ($transfer->status !== AssetTransfer::STATUS_PENDING) {
                return back()->with('error', 'This transfer cannot be put on hold because it is not in pending state.');
            }

            $transfer->update(['status' => AssetTransfer::STATUS_HOLD]);

            $this->notificationService->notify(
                $transfer->user,
                [
                    'type' => 'info',
                    'title' => 'Transfer On Hold',
                    'message' => "Your transfer ({$transfer->reference_number}) has been placed on hold.",
                    'notifiable_type' => $transfer->transfer_type === AssetTransfer::TYPE_IN ?
                        AssetTransfer::NOTIFICATION_TRANSFER_IN_FAILED :
                        AssetTransfer::NOTIFICATION_TRANSFER_FAILED,
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'reference_number' => $transfer->reference_number,
                        'amount' => $transfer->amount,
                        'currency' => $transfer->currency->symbol,
                        'fee' => $transfer->fee,
                        'hold_at' => now()->format('Y-m-d H:i:s'),
                        'hold_by' => 'Admin'
                    ]
                ],
                ['database', 'email']
            );

            return back()->with('success', 'Transfer has been put on hold successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to put transfer on hold: ' . $e->getMessage());
        }
    }

    /**
     * Request OTP for creating payment record
     */
    public function requestCreateOtp(AssetTransfer $transfer)
    {
        try {
            if ($transfer->transfer_type !== AssetTransfer::TYPE_OUT) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment records can only be created for outgoing transfers.'
                ], 400);
            }

            if ($transfer->latestTransferOutPayment()->where('payment_status', 'pending')->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A payment record already exists for this transfer.'
                ], 400);
            }

            // Generate OTP and store in session/cache
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $cacheKey = 'create_payment_otp_' . $transfer->id . '_' . Auth::id();

            // Store OTP in cache for 10 minutes
            cache()->put($cacheKey, $otp, now()->addMinutes(10));

            // Send OTP email
            $adminUser = Auth::user();
            $this->notificationService->notify(
                $adminUser,
                [
                    'type' => 'info',
                    'title' => 'OTP for Payment Creation',
                    'message' => "Your OTP for creating payment record for transfer {$transfer->reference_number} is: {$otp}. This OTP is valid for 10 minutes.",
                    'notifiable_type' => 'otp_create_payment',
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'otp' => $otp,
                        'transfer_reference' => $transfer->reference_number,
                        'amount' => $transfer->amount,
                        'currency' => $transfer->currency->symbol,
                        'expires_at' => now()->addMinutes(10)->format('Y-m-d H:i:s')
                    ]
                ],
                ['email']
            );

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create payment record with OTP verification
     */
    public function createPaymentWithOtp(AssetTransfer $transfer, Request $request)
    {
        try {
            $request->validate([
                'otp_code' => 'required|string|size:6'
            ]);

            // Verify OTP
            $cacheKey = 'create_payment_otp_' . $transfer->id . '_' . Auth::id();
            $cachedOtp = cache()->get($cacheKey);

            if (!$cachedOtp || $cachedOtp !== $request->otp_code) {
                return back()->with('error', 'Invalid or expired OTP. Please request a new OTP.');
            }

            // Clear the OTP from cache
            cache()->forget($cacheKey);

            // Call the original createPayment method
            return $this->createPayment($transfer);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create payment with OTP: ' . $e->getMessage());
        }
    }

    /**
     * Request OTP for sending payment
     */
    public function requestSendOtp(AssetTransfer $transfer)
    {
        try {
            $latestPayment = $transfer->latestTransferOutPayment;

            if (!$latestPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No payment record found for this transfer.'
                ], 400);
            }

            if (!$latestPayment->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment can only be sent if it is currently pending.'
                ], 400);
            }

            // Generate OTP and store in session/cache
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $cacheKey = 'send_payment_otp_' . $transfer->id . '_' . Auth::id();

            // Store OTP in cache for 10 minutes
            cache()->put($cacheKey, $otp, now()->addMinutes(10));

            // Send OTP email
            $adminUser = Auth::user();
            $this->notificationService->notify(
                $adminUser,
                [
                    'type' => 'warning',
                    'title' => 'OTP for Payment Authorization',
                    'message' => "Your OTP for authorizing payment for transfer {$transfer->reference_number} is: {$otp}. This OTP is valid for 10 minutes. CRITICAL: This will initiate payment of {$transfer->amount} {$transfer->currency->symbol}.",
                    'notifiable_type' => 'otp_send_payment',
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'otp' => $otp,
                        'transfer_reference' => $transfer->reference_number,
                        'amount' => $transfer->amount,
                        'currency' => $transfer->currency->symbol,
                        'transaction_id' => $latestPayment->transaction_id,
                        'expires_at' => now()->addMinutes(10)->format('Y-m-d H:i:s')
                    ]
                ],
                ['email']
            );

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send payment with OTP verification
     */
    public function sendPaymentWithOtp(AssetTransfer $transfer, Request $request)
    {
        try {
            $request->validate([
                'otp_code' => 'required|string|size:6'
            ]);

            // Verify OTP
            $cacheKey = 'send_payment_otp_' . $transfer->id . '_' . Auth::id();
            $cachedOtp = cache()->get($cacheKey);

            if (!$cachedOtp || $cachedOtp !== $request->otp_code) {
                return back()->with('error', 'Invalid or expired OTP. Please request a new OTP.');
            }

            // Clear the OTP from cache
            cache()->forget($cacheKey);

            // Call the original markPaymentSent method
            return $this->markPaymentSent($transfer, $request);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send payment with OTP: ' . $e->getMessage());
        }
    }
}
