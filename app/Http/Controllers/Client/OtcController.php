<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AssetAccount;
use App\Models\Currency;
use App\Models\OtcRequest;
use App\Utils\AssetOperations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtcController extends Controller
{
    private AssetOperations $assetOperations;

    public function __construct(AssetOperations $assetOperations)
    {
        $this->assetOperations = $assetOperations;
    }

    public function index()
    {
        $currencies = Currency::all();
        return view('client.otc.index', compact('currencies'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'from_amount' => 'required|numeric|min:0',
            'from_currency' => 'required|exists:currencies,id',
            'to_currency' => 'required|exists:currencies,id'
        ]);

        $fromCurrency = Currency::find($request->get('from_currency'));
        $toCurrency = Currency::find($request->get('to_currency'));

        return response()->json([
            'success' => true,
            'data' => $this->assetOperations->calculateOtcTransferDetails(
                amount: $request->get('from_amount'),
                from: $fromCurrency,
                to: $toCurrency,
                user: $request->user()
            )
        ]);
    }

    public function confirmExchange(Request $request)
    {
        $request->validate([
            'from_amount' => 'required|numeric|min:0',
            'from_currency' => 'required|exists:currencies,id',
            'to_currency' => 'required|exists:currencies,id',
            'terms' => 'accepted',
        ]);

        $fromCurrency = Currency::find($request->get('from_currency'));
        $toCurrency = Currency::find($request->get('to_currency'));

        // Calculate exchange details using new AssetOperations
        $calculation = $this->assetOperations->calculateOtcTransferDetails(
            amount: $request->get('from_amount'),
            from: $fromCurrency,
            to: $toCurrency,
            user: $request->user()
        );
        $fromAccount=AssetAccount::query()->where('user_id', auth()->user()->id)->where('currency_id', $fromCurrency->id)->first();

        if ($fromAccount->balance<$request->get('from_amount')+$calculation['fee']) {
            // Check if user has sufficient balance
            return redirect()->back()->with('error', 'Insufficient balance');
        }
        try {
            DB::beginTransaction();

            // Create OTC request with reference number
            $otcRequest = OtcRequest::create([
                'user_id' => $request->user()->id,
                'from_currency_id' => $fromCurrency->id,
                'to_currency_id' => $toCurrency->id,
                'from_amount' => $request->get('from_amount'),
                'to_amount' => $calculation['converted_amount'],
                'exchange_rate' => $calculation['rate'],
                'transaction_cost' => $calculation['otc_cost'],
                'network_fee' => $calculation['fee'],
                'status' => 'pending',
                'reference_number' => Str::uuid()
            ]);

            $fromAccount->balance=$fromAccount->balance-($request->get('from_amount')+$calculation['fee']);
            $fromAccount->save();
            // Here you would typically:
            // 1. Check if user has sufficient balance
            // 2. Lock the required amount
            // 3. Process the exchange
            // 4. Update the user's balances
            // 5. Update the OTC request status

            DB::commit();
            return redirect()->route('client.otc.success', ['id' => $otcRequest->id])->with('success', 'OTC exchange completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process OTC request. Please try again.');
        }
    }

    public function show(Request $request, $id)
    {
        $otcRequest = OtcRequest::with(['fromCurrency', 'toCurrency'])->findOrFail($id);

        // Ensure the user can only view their own OTC requests
        if ($otcRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('client.otc.show', compact('otcRequest'));
    }

    public function showSuccess(Request $request, $id)
    {
        $otcRequest = OtcRequest::with(['fromCurrency', 'toCurrency'])->findOrFail($id);

        // Ensure the user can only view their own OTC requests
        if ($otcRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('client.otc.success', compact('otcRequest'));
    }

    public function isExchangePossible(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|exists:currencies,id',
            'to_currency' => 'required|exists:currencies,id'
        ]);

        $fromCurrency = Currency::find($request->get('from_currency'));
        $toCurrency = Currency::find($request->get('to_currency'));
        if ($fromCurrency->type == "fiat" && $fromCurrency->symbol != 'USD' && $toCurrency->type != "fiat") {
            return response()->json([
                'success' => false,
                'message' => "You cannot convert Fiat Currency Directly"
            ], 403);
        }

        return response()->json(['success' => true]);
    }
}
