<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetTransfer;
use Illuminate\Http\Request;

class AssetTransferController extends Controller
{
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

    public function verify(AssetTransfer $transfer)
    {
        try {
            if ($transfer->status !== 'pending') {
                return back()->with('error', 'This transfer cannot be verified because it is not in pending state.');
            }
            $transfer->update(['status' => 'completed']);

            return back()->with('success', 'Transfer has been verified successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to verify transfer: ' . $e->getMessage());
        }
    }

    public function reject(AssetTransfer $transfer)
    {
        try {
            if ($transfer->status !== 'processing') {
                return back()->with('error', 'This transfer cannot be rejected because it is not in processing state.');
            }

            $transfer->update(['status' => 'failed']);

            return back()->with('success', 'Transfer has been rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject transfer: ' . $e->getMessage());
        }
    }
}
