<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayFactory;
use App\Models\ApiClient;
use App\Models\PaymentTransaction;
use App\Models\CryptoPaymentOrder;
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
            'payment_method' => 'required|in:credit_card,ngenius_card,crypto'
        ]);

        $paymentMethod = $request->payment_method;

        // Update transaction with selected gateway
        $gatewayName = $this->mapPaymentMethodToGateway($paymentMethod);

        $transaction->update([
            'gateway_name' => $gatewayName,
            'status' => PaymentTransaction::STATUS_PENDING,
            'stage' => PaymentTransaction::STAGE_GATEWAY_PROCESSING,
            'payment_method' => $paymentMethod,
        ]);

        // Generate payment URL based on selected method
        try {
            $paymentUrl = $this->generatePaymentUrl($transaction, $gatewayName);
            $transaction->update(['payment_url' => $paymentUrl]);

            // Redirect to appropriate payment gateway
            return redirect($paymentUrl);
        } catch (\Exception $e) {
            // Return error view instead of JSON
            return $this->renderErrorPage(
                title: 'Payment Processing Error',
                subtitle: 'Unable to process your payment request',
                message: 'Failed to create the payment. Please try again or contact support if the issue persists.',
                transaction: $transaction,
                errorCode: 'PAYMENT_CREATION_FAILED',
                suggestions: [
                    'Check your internet connection and try again',
                    'Verify your payment details are correct',
                    'Try using a different payment method',
                    'Contact support if the problem continues'
                ]
            );
        }



    }

    /**
     * Map payment method to gateway name
     */
    private function mapPaymentMethodToGateway(string $paymentMethod): string
    {
        return match ($paymentMethod) {
            'credit_card' => 'ngenius',
            'ngenius_card' => 'ngenius',
            'crypto' => 'trongrid',
            default => 'payop'
        };
    }

    /**
     * Generate payment URL based on payment method
     * @throws \Exception
     */
    private function generatePaymentUrl(PaymentTransaction $transaction, string $gatewayName): string
    {
        // If gateway is a high-level category (card/crypto), choose concrete provider via limits
        $providersByCategory = config('constants.internal_payment_providers');
        $selectedGateway = $gatewayName;
        if (array_key_exists($gatewayName, $providersByCategory)) {
            $selectedGateway = $this->clientPaymentService->selectGatewayForCategory(
                $transaction->apiClient,
                $gatewayName,
                (float)$transaction->amount
            );

            if (!$selectedGateway) {
                abort(422, 'No available payment provider with remaining limit for the selected method');
            }

            // Persist the concrete gateway on the transaction
            $transaction->update(['gateway_name' => $selectedGateway]);
        }

        $paymentData = [
            'amount' => $transaction->amount,
            'currency' => strtoupper($transaction->currency),
            'description' => $transaction->description,
            'customer_email' => $transaction->customer_email ?? '',
            'customer_name' => $transaction->customer_name ?? '',
            'customer_phone' => $transaction->customer_phone ?? '',
            'language' => $transaction->language ?? 'en',
            'return_url' => $transaction->return_url,
            'cancel_url' => $transaction->cancel_url,
            'metadata' => $transaction->metadata ?? [],
        ];
        $result = $this->clientPaymentService->createPayment($transaction->apiClient, $paymentData, $selectedGateway, $transaction);

        if ($result['success']) {
            return $result['data']['payment_url'];
        }
        throw new \Exception($result['message']);


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
     * Display crypto checkout page
     */
    public function cryptoCheckout(Request $request, string $orderId): View
    {
        // Find the crypto payment order by order ID
        $cryptoOrder = CryptoPaymentOrder::where('order_id', $orderId)->first();

        if (!$cryptoOrder) {
            abort(404, 'Crypto payment order not found');
        }

        // Check if payment order is expired
        if ($cryptoOrder->isExpired()) {
            $cryptoOrder->markAsExpired();
            abort(410, 'Payment session has expired');
        }

        // Check if payment order is no longer awaiting payment
        if (!$cryptoOrder->isAwaitingPayment()) {
            abort(400, 'Payment session is no longer valid');
        }

        // Get the main wallet address from the order
        $walletAddress = $cryptoOrder->wallet_address;

        return view('checkout.crypto', compact('cryptoOrder', 'walletAddress'));
    }


    /**
     * Check payment Status
     */
    public function checkPaymentStatus(Request $request, $transactionId)
    {
        // First try to find as a crypto payment order (new system)
        $cryptoOrder = CryptoPaymentOrder::where('order_id', $transactionId)->first();

        if ($cryptoOrder) {
            // Check if payment has expired
            if ($cryptoOrder->isExpired()) {
                $cryptoOrder->markAsExpired();
            }

            return response()->json([
                "success" => true,
                "data" => [
                    "transaction_id" => $cryptoOrder->order_id,
                    "status" => $cryptoOrder->status,
                    "amount" => $cryptoOrder->fingerprint_amount,
                    "original_amount" => $cryptoOrder->original_amount,
                    "currency" => $cryptoOrder->payment_currency,
                    "original_currency" => $cryptoOrder->original_currency,
                    "wallet_address" => $cryptoOrder->wallet_address,
                    "payment_url" => $cryptoOrder->payment_url,
                    "expires_at" => $cryptoOrder->expires_at?->toISOString(),
                    "paid_at" => $cryptoOrder->paid_at?->toISOString(),
                    "gateway_transaction_id" => $cryptoOrder->gateway_transaction_id,
                    "fingerprint_code" => $cryptoOrder->fingerprint_code,
                    "is_expired" => $cryptoOrder->isExpired(),
                    "is_awaiting_payment" => $cryptoOrder->isAwaitingPayment(),
                ]
            ]);
        }

        // Fallback to old PaymentTransaction system for backward compatibility
        $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            abort(404, 'Payment not found');
        }

        // Check if transaction is expired (24 hours)
        if ($transaction->created_at->addHours(24)->isPast()) {
            abort(410, 'Payment session has expired');
        }

        return response()->json([
            "success" => true,
            "data" => $transaction
        ]);
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

    /**
     * Show error page with customizable parameters
     */
    public function showError(Request $request): View
    {
        $title = $request->query('title', 'Payment Error');
        $subtitle = $request->query('subtitle', 'An error occurred while processing your payment');
        $message = $request->query('message', 'Your payment could not be processed at this time.');
        $errorCode = $request->query('error_code');
        $transactionId = $request->query('transaction_id');
        $retryUrl = $request->query('retry_url');
        $cancelUrl = $request->query('cancel_url');
        $backUrl = $request->query('back_url');

        $transaction = null;
        if ($transactionId) {
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();
        }

        $suggestions = $request->query('suggestions');
        if ($suggestions) {
            $suggestions = is_string($suggestions) ? explode(',', $suggestions) : $suggestions;
        }

        return view('checkout.error', compact(
            'title', 'subtitle', 'message', 'errorCode', 'transaction',
            'retryUrl', 'cancelUrl', 'backUrl', 'suggestions'
        ));
    }

    /**
     * Helper method to render error page with transaction context
     */
    private function renderErrorPage(
        string $title,
        string $subtitle,
        string $message,
        ?PaymentTransaction $transaction = null,
        ?string $errorCode = null,
        ?string $retryUrl = null,
        ?string $cancelUrl = null,
        ?string $backUrl = null,
        ?array $suggestions = null
    ): View {
        return view('checkout.error', [
            'title' => $title,
            'subtitle' => $subtitle,
            'message' => $message,
            'errorCode' => $errorCode,
            'transaction' => $transaction,
            'retryUrl' => $retryUrl,
            'cancelUrl' => $cancelUrl,
            'backUrl' => $backUrl,
            'suggestions' => $suggestions
        ]);
    }
}
