<?php

namespace App\Http\Controllers;

use App\Services\OppwaService;
use App\Models\OppwaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OppwaController extends Controller
{
    private OppwaService $oppwaService;

    public function __construct(OppwaService $oppwaService)
    {
        $this->oppwaService = $oppwaService;
    }

    /**
     * Show payment page
     */
    public function showPaymentPage(Request $request, string $transactionId)
    {
        $transaction = $this->oppwaService->getTransaction($transactionId);

        if (!$transaction) {
            return view('oppwa.error', [
                'error' => 'Transaction not found',
                'message' => 'The payment transaction you are looking for does not exist or has expired.'
            ]);
        }

        if ($transaction->isExpired()) {
            return view('oppwa.error', [
                'error' => 'Transaction Expired',
                'message' => 'This payment session has expired. Please create a new payment.'
            ]);
        }

        if ($transaction->isSuccessful()) {
            return view('oppwa.success', [
                'transaction' => $transaction
            ]);
        }

        if ($transaction->hasFailed()) {
            return view('oppwa.error', [
                'error' => 'Payment Failed',
                'message' => $transaction->failure_reason ?? 'The payment could not be processed.',
                'transaction' => $transaction
            ]);
        }

        return view('oppwa.payment', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Process payment form submission
     */
    public function processPayment(Request $request, string $transactionId)
    {
        $transaction = $this->oppwaService->getTransaction($transactionId);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'error' => 'Transaction not found'
            ], 404);
        }

        if ($transaction->isExpired()) {
            return response()->json([
                'success' => false,
                'error' => 'Transaction expired'
            ], 400);
        }

        if ($transaction->isSuccessful() || $transaction->hasFailed()) {
            return response()->json([
                'success' => false,
                'error' => 'Transaction already processed'
            ], 400);
        }

        // Update transaction status to processing
        $transaction->updateStatus(OppwaTransaction::STATUS_PROCESSING);

        return response()->json([
            'success' => true,
            'message' => 'Payment processing initiated',
            'payment_url' => $transaction->payment_url
        ]);
    }

    /**
     * Handle payment result callback
     */
    public function handlePaymentResult(Request $request, string $transactionId)
    {
        $transaction = $this->oppwaService->getTransaction($transactionId);

        if (!$transaction) {
            return view('oppwa.error', [
                'error' => 'Transaction not found',
                'message' => 'The payment transaction you are looking for does not exist.'
            ]);
        }

        // Get resourcePath from query parameters
        $checkoutId=$request->query('id');
        if ($checkoutId) {
            // Get updated payment status from OPPWA
            $result = $this->oppwaService->getPaymentStatus($checkoutId);
            if ($result['success']) {
                $transaction = $this->oppwaService->getTransaction($transactionId);
            }
        }

        if ($transaction->isSuccessful()) {
            return view('oppwa.success', [
                'transaction' => $transaction
            ]);
        } elseif ($transaction->hasFailed()) {
            return view('oppwa.error', [
                'error' => 'Payment Failed',
                'message' => $transaction->failure_reason ?? 'The payment could not be processed.',
                'transaction' => $transaction
            ]);
        } else {
            return view('oppwa.pending', [
                'transaction' => $transaction
            ]);
        }
    }

    /**
     * Create a new payment
     */
    public function createPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string|max:500',
            'customer_email' => 'nullable|email|max:255',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'external_order_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if currency is supported
        if (!$this->oppwaService->isCurrencySupported($request->currency)) {
            return back()->withErrors([
                'currency' => 'Currency not supported. Supported currencies: ' . implode(', ', $this->oppwaService->getSupportedCurrencies())
            ])->withInput();
        }

        // Prepare payment data
        $paymentData = [
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency),
            'description' => $request->description,
            'customer_email' => $request->customer_email,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'external_order_id' => $request->external_order_id,
            'result_url' => route('oppwa.result', ['transactionId' => 'PLACEHOLDER']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        $result = $this->oppwaService->createCheckout($paymentData);

        if (!$result['success']) {
            return back()->withErrors([
                'payment' => 'Failed to create payment: ' . $result['error']
            ])->withInput();
        }

        // Update result URL with actual transaction ID
        $transaction = $this->oppwaService->getTransaction($result['transaction_id']);
        $transaction->update([
            'result_url' => route('oppwa.result', ['transactionId' => $result['transaction_id']])
        ]);

        return redirect()->route('oppwa.payment', ['transactionId' => $result['transaction_id']]);
    }

    /**
     * Show payment form
     */
    public function showPaymentForm()
    {
        return view('oppwa.create');
    }
}
