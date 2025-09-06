<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettlementController extends Controller
{
    /**
     * Display a listing of settlements
     */
    public function index(Request $request)
    {
        $query = Settlement::with(['user:id,name,email']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by reference ID
        if ($request->filled('search')) {
            $query->where('reference_id', 'like', '%' . $request->search . '%');
        }

        $settlements = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $users = User::role('client')->orderBy('name')->get();
        $statuses = [
            Settlement::STATUS_PENDING,
            Settlement::STATUS_PROCESSING,
            Settlement::STATUS_COMPLETED,
            Settlement::STATUS_FAILED,
            Settlement::STATUS_CANCELLED,
        ];

        // Get statistics
        $stats = [
            'total_pending' => Settlement::where('status', Settlement::STATUS_PENDING)->count(),
            'total_processing' => Settlement::where('status', Settlement::STATUS_PROCESSING)->count(),
            'total_completed' => Settlement::where('status', Settlement::STATUS_COMPLETED)->count(),
            'total_amount_pending' => Settlement::where('status', Settlement::STATUS_PENDING)->sum('amount'),
            'total_amount_completed' => Settlement::where('status', Settlement::STATUS_COMPLETED)->sum('net_amount'),
        ];

        return view('admin.settlements.index', compact(
            'settlements',
            'users',
            'statuses',
            'stats'
        ));
    }

    /**
     * Show settlement details
     */
    public function show(Settlement $settlement)
    {
        $settlement->load(['user', 'processedBy']);
        
        return view('admin.settlements.show', compact('settlement'));
    }

    /**
     * Mark settlement as processing
     */
    public function markAsProcessing(Settlement $settlement)
    {
        $adminId = Auth::id();
        
        $settlement->markAsProcessing($adminId);

        Log::info('Settlement marked as processing', [
            'settlement_id' => $settlement->id,
            'reference_id' => $settlement->reference_id,
            'admin_id' => $adminId
        ]);

        return redirect()->back()->with('success', 'Settlement marked as processing.');
    }

    /**
     * Complete settlement
     */
    public function complete(Request $request, Settlement $settlement)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            $adminId = Auth::id();
            $adminNotes = $request->input('admin_notes');
            
            $settlement->markAsCompleted($adminId, $adminNotes);

            Log::info('Settlement completed', [
                'settlement_id' => $settlement->id,
                'reference_id' => $settlement->reference_id,
                'admin_id' => $adminId,
                'amount' => $settlement->amount,
                'net_amount' => $settlement->net_amount
            ]);

            return redirect()->back()->with('success', 'Settlement completed successfully. Funds transferred to user\'s USD account.');

        } catch (\Exception $e) {
            Log::error('Settlement completion failed', [
                'settlement_id' => $settlement->id,
                'reference_id' => $settlement->reference_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to complete settlement: ' . $e->getMessage());
        }
    }

    /**
     * Mark settlement as failed
     */
    public function markAsFailed(Request $request, Settlement $settlement)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $adminId = Auth::id();
        $reason = $request->input('reason');
        
        $settlement->markAsFailed($adminId, $reason);

        Log::info('Settlement marked as failed', [
            'settlement_id' => $settlement->id,
            'reference_id' => $settlement->reference_id,
            'admin_id' => $adminId,
            'reason' => $reason
        ]);

        return redirect()->back()->with('success', 'Settlement marked as failed.');
    }

    /**
     * Cancel settlement
     */
    public function cancel(Request $request, Settlement $settlement)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $adminId = Auth::id();
        $reason = $request->input('reason');
        
        $settlement->cancel($adminId, $reason);

        Log::info('Settlement cancelled', [
            'settlement_id' => $settlement->id,
            'reference_id' => $settlement->reference_id,
            'admin_id' => $adminId,
            'reason' => $reason
        ]);

        return redirect()->back()->with('success', 'Settlement cancelled.');
    }
}
