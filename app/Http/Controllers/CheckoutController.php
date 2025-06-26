<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayFactory;
use App\Models\ApiClient;
use App\Models\PaymentTransaction;
use App\Services\ClientPaymentService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected ClientPaymentService $clientPaymentService;

    public function __construct(
        ClientPaymentService $clientPaymentService
    )
    {
        $this->clientPaymentService = $clientPaymentService;
    }

    /**
     * Display the checkout page
     */
    public function show(Request $request, string $session): View
    {
        $transaction = PaymentTransaction::where('checkout_session_id', $session)->first();

        if (!$transaction) {
            abort(404, 'Payment session not found');
        }

        // Check if transaction is expired (24 hours)
        if ($transaction->created_at->addHours(24)->isPast()) {
            abort(410, 'Payment session has expired');
        }

        // Check if transaction is already processed
        if (!$transaction->isCheckoutPending()) {
            abort(400, 'Payment session is no longer valid');
        }

        return view('checkout.payment', compact('transaction'));
    }

    /**
     * Handle payment method selection
     */
    public function selectPaymentMethod(Request $request, string $session)
    {
        $transaction = PaymentTransaction::where('checkout_session_id', $session)->first();

        if (!$transaction) {
            abort(404, 'Payment session not found');
        }

        if (!$transaction->isCheckoutPending()) {
            abort(400, 'Payment session is no longer valid');
        }

        // Validate payment method selection
        $request->validate([
            'payment_method' => 'required|in:credit_card,crypto'
        ]);

        $paymentMethod = $request->payment_method;

        // Update transaction with selected gateway
        $gatewayName = $this->mapPaymentMethodToGateway($paymentMethod);

        $transaction->update([
            'gateway_name' => $gatewayName,
            'status' => PaymentTransaction::STATUS_PENDING,
            'stage' => PaymentTransaction::STAGE_GATEWAY_PROCESSING,
        ]);

        // Generate payment URL based on selected method
        $paymentUrl = $this->generatePaymentUrl($transaction, $paymentMethod);

        $transaction->update(['payment_url' => $paymentUrl]);

        // Redirect to appropriate payment gateway
        return redirect($paymentUrl);
    }

    /**
     * Map payment method to gateway name
     */
    private function mapPaymentMethodToGateway(string $paymentMethod): string
    {
        return match ($paymentMethod) {
            'credit_card' => 'payop',
            'crypto' => 'crypto_gateway',
            default => 'payop'
        };
    }

    /**
     * Generate payment URL based on payment method
     */
    private function generatePaymentUrl(PaymentTransaction $transaction, string $paymentMethod): string
    {

        $paymentData = [
            'amount' => $transaction->amount,
            'currency' => strtoupper($transaction->currency),
            'description' => $transaction->description ,
            'customer_email' => $transaction->customer_email ?? '',
            'customer_name' => $transaction->customer_name ?? '',
            'customer_phone' => $transaction->customer_phone ?? '',
            'language' => $transaction->language ?? 'en',
            'return_url' => $transaction->return_url,
            'cancel_url' => $transaction->cancel_url,
            'metadata' => $transaction->metadata ?? [],
        ];

        $result = $this->clientPaymentService->createPayment($transaction->apiClient, $paymentData);
        return $result['data']['payment_url'];
    }

    /**
     * Display payment success page
     */
    public function success(Request $request): View
    {
        $transactionId = $request->query('transaction_id');
        $transaction = null;
        
        if ($transactionId) {
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();
        }

        return view('checkout.success', compact('transaction'));
    }

    /**
     * Display payment failed page
     */
    public function failed(Request $request): View
    {
        $transactionId = $request->query('transaction_id');
        $transaction = null;
        $errorMessage = $request->query('error', 'Payment could not be processed');
        
        if ($transactionId) {
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();
        }

        return view('checkout.failed', compact('transaction', 'errorMessage'));
    }
}
