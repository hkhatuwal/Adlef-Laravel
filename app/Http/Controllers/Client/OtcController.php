<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\OtcRequest;
use App\Utils\FeeCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtcController extends Controller
{
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
            'data' => FeeCalculator::calculateExchangeRateAndFee($request->get('from_amount'), $fromCurrency, $toCurrency)
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

        // Calculate exchange rate and fee
        $calculation = FeeCalculator::calculateExchangeRateAndFee($request->get('from_amount'), $fromCurrency, $toCurrency);
        
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
                'network_fee' => $calculation['fee'],
                'status' => 'pending',
                'reference_number' => Str::uuid()
            ]);

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
}
