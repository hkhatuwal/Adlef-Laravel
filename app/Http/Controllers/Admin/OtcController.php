<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetAccount;
use App\Models\OtcRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtcController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of OTC requests.
     */
    public function index()
    {
        $otcRequests = OtcRequest::with(['user', 'fromCurrency', 'toCurrency'])
            ->latest()
            ->paginate(10);

        return view('admin.otc.index', compact('otcRequests'));
    }

    /**
     * Display the specified OTC request.
     */
    public function show(OtcRequest $otc)
    {
        $otc->load(['user', 'fromCurrency', 'toCurrency', 'activities']);

        return view('admin.otc.show', compact('otc'));
    }

    /**
     * Process the OTC request.
     */
    public function process(OtcRequest $otc, Request $request)
    {
        $oldFee=$otc->network_fee;
        $newFee=$request->network_fee;
        $feeDifference=$newFee-$oldFee;


        $otc->status = 'completed';
        $otc->network_fee = $newFee;
        $otc->save();
        $toAccount=AssetAccount::query()->where('user_id', $otc->user_id)->where('currency_id', $otc->to_currency_id)->first();
        $fromAccount=AssetAccount::query()->where('user_id', $otc->user_id)->where('currency_id', $otc->from_currency_id)->first();


        if ($feeDifference>0){
            $fromAccount->update([
                'balance' => $fromAccount->balance - $feeDifference
            ]);
        }

        $toAccount->balance=$toAccount->balance+$otc->to_amount;
        $toAccount->save();

        // Send notification to user
        $this->notificationService->notify(
            $otc->user,
            [
                'type' => 'success',
                'title' => 'OTC Trade Processed',
                'message' => "Your OTC trade request has been processed successfully.",
                'notifiable_type' => OtcRequest::NOTIFICATION_OTC_SUCCESS,
                'notifiable_id' => $otc->id,
                'metadata' => [
                    'from_currency' => $otc->fromCurrency->symbol,
                    'to_currency' => $otc->toCurrency->symbol,
                    'from_amount' => $otc->from_amount,
                    'to_amount' => $otc->to_amount,
                    'network_fee' => $otc->network_fee,
                    'processed_at' => now()->format('Y-m-d H:i:s'),
                    'processed_by' => Auth::user()->name
                ]
            ],
            ['database', 'email']
        );

        return redirect()->route('admin.otc.show', $otc)
            ->with('success', 'OTC trade is now being processed.');
    }

    /**
     * Hold the OTC request.
     */
    public function hold(OtcRequest $otc, Request $request)
    {
        $otc->status = 'on-hold';
        $otc->hold_reason = $request->reason;
        $otc->save();

        // Send notification to user
        $this->notificationService->notify(
            $otc->user,
            [
                'type' => 'warning',
                'title' => 'Your OTC instruction has been put on hold',
                'message' => "Your OTC instruction has been put on hold and is under review.",
                'notifiable_type' => OtcRequest::NOTIFICATION_OTC_HOLD,
                'notifiable_id' => $otc->id,
                'metadata' => [
                    'reference_code' => $otc->reference_code ?? $otc->id,
                    'amount' => $otc->from_amount,
                    'currency' => $otc->fromCurrency->symbol,
                    'created_at' => $otc->created_at->format('Y-m-d H:i:s'),
                    'reason' => $otc->hold_reason,
                    'held_by' => Auth::user()->name,
                    'held_at' => now()->format('Y-m-d H:i:s')
                ]
            ],
            ['database', 'email']
        );

        return redirect()->route('admin.otc.show', $otc)
            ->with('success', 'OTC trade has been put on hold.');
    }

    /**
     * Reject the OTC request.
     */
    public function reject(OtcRequest $otc, Request $request)
    {
        $otc->status = 'failed';
        $otc->failure_reason = $request->reason;
        $otc->save();

        // Send notification to user
        $this->notificationService->notify(
            $otc->user,
            [
                'type' => 'error',
                'title' => 'Your OTC instruction has been cancelled',
                'message' => "Your OTC instruction has been cancelled.",
                'notifiable_type' => OtcRequest::NOTIFICATION_OTC_FAILED,
                'notifiable_id' => $otc->id,
                'metadata' => [
                    'reference_code' => $otc->reference_code,
                    'amount' => $otc->from_amount,
                    'currency' => $otc->fromCurrency->symbol,
                    'created_at' => $otc->created_at->format('Y-m-d H:i:s'),
                    'reason' => $otc->failure_reason
                ]
            ],
            ['database', 'email']
        );

        return redirect()->route('admin.otc.show', $otc)
            ->with('success', 'OTC trade has been rejected.');
    }
}
