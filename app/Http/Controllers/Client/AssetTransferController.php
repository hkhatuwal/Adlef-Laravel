<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AssetTransfer;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Account;

// Added this line

class AssetTransferController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('client.transfer.index', compact('currencies'));
    }

    public function createTransferInstruction(Request $request)
    {
        // This will be implemented later for handling the transfer
        return response()->json(['message' => 'Transfer instruction created successfully']);
    }

    public function transferIn(Request $request)
    {
        // Get the currency ID from the request
        $currencyId = $request->query('currency_id');

        // Get the asset account for the current user and selected currency
        $fromAccounts = auth()->user()->bankAccounts;

        $assetAccount = auth()->user()->assetAccounts()
            ->where('currency_id', $currencyId)
            ->with('currency')
            ->firstOrFail();

        $sourceOptions = config('constants.source_funds');

        return view('client.transfer.transfer-in', compact('assetAccount', 'sourceOptions', 'fromAccounts'));
    }

    public function transferOut(Request $request)
    {
        // Get user's bank accounts for the from account selection
        $currencyId = $request->query('currency_id');
        $assetAccount = auth()->user()->assetAccounts()
            ->where('currency_id', $currencyId)
            ->with('currency')
            ->firstOrFail();

        if ($assetAccount->isUSD()) {
            $fromAccounts = auth()->user()->bankAccounts;
        } else {
            $fromAccounts = auth()->user()->cryptoWallets()->where('currency_id', $currencyId)->get();
        }
        $sourceOptions = config('constants.source_funds');

        return view('client.transfer.transfer-out', compact('fromAccounts', 'assetAccount', 'sourceOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_account' => 'required|exists:accounts,id',
            'to_account' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:100',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Create the transfer record
            $transfer = AssetTransfer::create([
                'user_id' => auth()->id(),
                'from_account_id' => $validated['from_account'],
                'to_account_id' => $validated['to_account'],
                'amount' => $validated['amount'],
                'notes' => $validated['notes'],
                'status' => 'pending',
                'reference' => 'TRF-' . strtoupper(uniqid()),
            ]);

            DB::commit();

            return redirect()
                ->route('client.transfer.review', $transfer->id)
                ->with('success', 'Transfer created successfully. Please review the details.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create transfer. Please try again.');
        }
    }

    public function storeTransferIn(Request $request)
    {
        $validatedData = $request->validate([
            'from_account' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        AssetTransfer::create([
            'from_account_id' => $validatedData['from_account'],
            'to_account_id' => auth()->id(),
            'amount' => $validatedData['amount'],
            'status' => 'pending',
            'transfer_type' => 'in',
        ]);

        return redirect()->back()->with('success', 'Transfer In request submitted successfully.');
    }

    public function storeTransferOut(Request $request)
    {
        $validatedData = $request->validate([
            'to_account' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        AssetTransfer::create([
            'from_account_id' => auth()->id(),
            'to_account_id' => $validatedData['to_account'],
            'amount' => $validatedData['amount'],
            'status' => 'pending',
            'transfer_type' => 'out',
        ]);

        return redirect()->back()->with('success', 'Transfer Out request submitted successfully.');
    }
}
