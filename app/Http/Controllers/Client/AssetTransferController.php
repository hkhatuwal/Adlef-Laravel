<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AssetAccount;
use App\Models\AssetTransfer;
use App\Models\BankAccount;
use App\Models\CryptoWallet;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Utils\FeeCalculator;

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

        if ($assetAccount->currency->isUSD()) {
            $fromAccounts = auth()->user()->bankAccounts;
        } else {
            $fromAccounts = auth()->user()->cryptoWallets()->where('currency_id', $currencyId)->get();
        }
        $sourceOptions = config('constants.source_funds');

        return view('client.transfer.transfer-out', compact('fromAccounts', 'assetAccount', 'sourceOptions'));
    }


    public function storeTransferIn(Request $request)
    {
        $isUSD = $request->boolean('isUSD');
        $rules = [
            'to_account' => 'required|exists:asset_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'currency_id' => 'required|exists:currencies,id',
        ];

        if ($isUSD) {
            $rules['from_account'] = 'required|exists:bank_accounts,id';
        }

        $validator = Validator::make($request->all(), $rules, [
            'from_account.required' => 'The from account is required for USD transfers.',
            'to_account.required' => 'The to account is required.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'currency_id' => 'required|numeric|min:0.01|exists:currencies,id',
            'amount.min' => 'The amount must be at least 0.01.',
            'currency_id.required' => 'The currency is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check your inputs.');
        }

        $transfer = AssetTransfer::create([
            'from_account_id' => $request->input('from_account'),
            'from_account_type'=>$isUSD?BankAccount::class:null,
            'to_account_type'=>AssetAccount::class,
            'to_account_id' => $request->input('to_account'),
            'amount' => $request->input('amount'),
            'currency_id' => $request->input('currency_id'),
            'reference_number' => Str::uuid(),
            'status' => 'pending',
            'fee'=>0,
            'transfer_type' => AssetTransfer::TYPE_IN,
        ]);

        // Load the currency relationship for the view

        return view('client.transfer.transfer-in-success', compact('transfer'));
    }

    public function storeTransferOut(Request $request)
    {
        $isUSD = $request->boolean('isUSD');
        $rules = [
            'from_account' => 'required|exists:asset_accounts,id',
            'to_account' =>  $isUSD ? 'required|exists:bank_accounts,id':'required|exists:crypto_wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'currency_id' => 'required|exists:currencies,id',
        ];



        $validator = Validator::make($request->all(), $rules, [
            'from_account.required' => 'The from account is required for transfers.',
            'to_account.required' => 'The to account is required.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'currency_id' => 'required|numeric|min:0.01|exists:currencies,id',
            'amount.min' => 'The amount must be at least 0.01.',
            'currency_id.required' => 'The currency is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check your inputs.');
        }

        $transfer = AssetTransfer::create([
            'from_account_type'=>AssetAccount::class,
            'to_account_type'=>$isUSD?BankAccount::class:CryptoWallet::class,
            'from_account_id' => $request->input('from_account'),
            'to_account_id' => $request->input('to_account'),
            'amount' => $request->input('amount'),
            'currency_id' => $request->input('currency_id'),
            'reference_number' => Str::uuid(),
            'status' => 'pending',
            'fee' => $request->input('fee'),
            'transfer_type' => AssetTransfer::TYPE_OUT,
        ]);

        // Return the transfer-out-success view with the transfer data
        return view('client.transfer.transfer-out-success', compact('transfer'));
    }

    public function calculateFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $currency = Currency::find($request->currency_id);
        $feeDetails = FeeCalculator::calculateTransferFee(
            $request->amount,
            $currency->symbol
        );

        return response()->json([
            'success' => true,
            'data' => $feeDetails
        ]);
    }

    public function show(Request $request,$id)
    {
        $transfer=AssetTransfer::find($id);
        $transfer->load(['currency','from_account','to_account']);
        return view('client.transfer.show', compact('transfer'));
    }
}
