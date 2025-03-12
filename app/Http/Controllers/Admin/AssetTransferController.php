<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetTransfer;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetTransferController extends Controller
{

    public function __construct(private  NotificationService $notificationService){

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
            'activities'
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
                    'verified_by' => \auth()->user()->id
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
                    'verified_by' => auth()->user()->name
                ]
            ],
            ['database', 'email']
        );
    }

    /**
     * Verify a transfer-in from API
     *
     * @param AssetTransfer $transfer
     * @param Request $request
     * @return void
     */
    public function verifyTransferInApi(AssetTransfer $transfer, Request $request)
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
                        'verified_by' => 'API Verification'
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
                    'notifiable_type' => AssetTransfer::NOTIFICATION_TRANSFER_FAILED,
                    'notifiable_id' => $transfer->id,
                    'metadata' => [
                        'reference_number' => $transfer->reference_number,
                        'amount' => $transfer->amount,
                        'currency' => $transfer->currency->symbol,
                        'fee' => $transfer->fee,
                        'verified_at' => now()->format('Y-m-d H:i:s'),
                        'verified_by' => 'API Verification'
                    ]
                ],
                ['database', 'email']
            );


            return back()->with('success', 'Transfer has been rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject transfer: ' . $e->getMessage());
        }
    }
}
