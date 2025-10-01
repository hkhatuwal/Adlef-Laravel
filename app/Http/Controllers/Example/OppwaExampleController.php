<?php

namespace App\Http\Controllers\Example;

use App\Http\Controllers\Controller;
use App\Services\OppwaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Example controller demonstrating OPPWA service usage
 * This is for reference only - remove in production
 */
class OppwaExampleController extends Controller
{
    private OppwaService $oppwaService;

    public function __construct(OppwaService $oppwaService)
    {
        $this->oppwaService = $oppwaService;
    }

    /**
     * Example: Create a simple payment
     */
    public function createSimplePayment(Request $request): JsonResponse
    {
        $paymentData = [
            'amount' => 50.00,
            'currency' => 'USD',
            'description' => 'Example payment',
            'customer_email' => 'customer@example.com',
            'customer_name' => 'John Doe',
            'external_order_id' => 'EXAMPLE-' . time(),
            'result_url' => route('oppwa.result', ['transactionId' => 'PLACEHOLDER']),
            'callback_url' => route('api.oppwa.webhook'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        $result = $this->oppwaService->createCheckout($paymentData);

        if (!$result['success']) {
            return response()->json([
                'error' => 'Payment creation failed',
                'details' => $result['error']
            ], 500);
        }

        // Update result URL with actual transaction ID
        $transaction = $this->oppwaService->getTransaction($result['transaction_id']);
        if ($transaction) {
            $transaction->update([
                'result_url' => route('oppwa.result', ['transactionId' => $result['transaction_id']])
            ]);
        }

        return response()->json([
            'message' => 'Payment created successfully',
            'transaction_id' => $result['transaction_id'],
            'payment_url' => $result['payment_url'],
            'redirect_url' => route('oppwa.payment', ['transactionId' => $result['transaction_id']])
        ]);
    }

    /**
     * Example: Check payment status
     */
    public function checkPaymentStatus(string $transactionId): JsonResponse
    {
        $transaction = $this->oppwaService->getTransaction($transactionId);

        if (!$transaction) {
            return response()->json([
                'error' => 'Transaction not found'
            ], 404);
        }

        // If transaction has OPPWA checkout ID, get latest status
        if ($transaction->oppwa_checkout_id) {
            $statusResult = $this->oppwaService->getPaymentStatus($transaction->oppwa_checkout_id);
            
            if ($statusResult['success']) {
                $transaction = $this->oppwaService->getTransaction($transactionId);
            }
        }

        return response()->json([
            'transaction' => $transaction->toApiResponse(),
            'is_successful' => $transaction->isSuccessful(),
            'is_pending' => $transaction->isPending(),
            'has_failed' => $transaction->hasFailed(),
        ]);
    }

    /**
     * Example: Get supported currencies
     */
    public function getSupportedCurrencies(): JsonResponse
    {
        return response()->json([
            'currencies' => $this->oppwaService->getSupportedCurrencies(),
            'count' => count($this->oppwaService->getSupportedCurrencies())
        ]);
    }

    /**
     * Example: Get supported payment methods
     */
    public function getSupportedPaymentMethods(): JsonResponse
    {
        return response()->json([
            'payment_methods' => $this->oppwaService->getSupportedPaymentMethods()
        ]);
    }

    /**
     * Example: Check configuration status
     */
    public function getConfigStatus(): JsonResponse
    {
        return response()->json([
            'config' => $this->oppwaService->getConfigStatus()
        ]);
    }

    /**
     * Example: Process webhook manually (for testing)
     */
    public function processWebhookManually(Request $request): JsonResponse
    {
        $webhookData = $request->all();
        
        $parsedData = $this->oppwaService->parseWebhookData($webhookData);
        
        return response()->json([
            'message' => 'Webhook data parsed',
            'parsed_data' => $parsedData,
            'original_data' => $webhookData
        ]);
    }
}
