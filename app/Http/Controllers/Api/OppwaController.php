<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OppwaService;
use App\Services\ClientPaymentService;
use App\Models\OppwaTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OppwaController extends Controller
{
    private OppwaService $oppwaService;
    private ClientPaymentService $clientPaymentService;

    public function __construct(OppwaService $oppwaService, ClientPaymentService $clientPaymentService)
    {
        $this->oppwaService = $oppwaService;
        $this->clientPaymentService = $clientPaymentService;
    }

    /**
     * Create a new OPPWA payment checkout
     */
    public function createCheckout(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'external_order_id' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'customer_email' => 'nullable|email|max:255',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'result_url' => 'nullable|url|max:500',
            'callback_url' => 'nullable|url|max:500',
            'payment_type' => 'nullable|string|in:DB,PA',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 400);
        }

        // Check if currency is supported
        if (!$this->oppwaService->isCurrencySupported($request->currency)) {
            return response()->json([
                'success' => false,
                'error' => 'Currency not supported',
                'supported_currencies' => $this->oppwaService->getSupportedCurrencies()
            ], 400);
        }

        // Prepare payment data
        $paymentData = [
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency),
            'external_order_id' => $request->external_order_id,
            'description' => $request->description,
            'customer_email' => $request->customer_email,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'result_url' => $request->result_url,
            'callback_url' => $request->callback_url,
            'payment_type' => $request->payment_type ?? 'DB',
            'api_client_id' => $request->user()?->id, // If authenticated
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $request->metadata,
        ];

        $result = $this->oppwaService->createCheckout($paymentData);

        if (!$result['success']) {
            return response()->json($result, 500);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ], 201);
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request, string $checkoutId): JsonResponse
    {
        $result = $this->oppwaService->getPaymentStatus($checkoutId);

        if (!$result['success']) {
            return response()->json($result, 500);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Get transaction by ID
     */
    public function getTransaction(Request $request, string $transactionId): JsonResponse
    {
        $transaction = $this->oppwaService->getTransaction($transactionId);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'error' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction->toApiResponse()
        ]);
    }

    /**
     * Get transaction by external order ID
     */
    public function getTransactionByExternalOrderId(Request $request, string $externalOrderId): JsonResponse
    {
        $transaction = $this->oppwaService->getTransactionByExternalOrderId($externalOrderId);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'error' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction->toApiResponse()
        ]);
    }

    /**
     * Get supported payment methods
     */
    public function getSupportedPaymentMethods(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->oppwaService->getSupportedPaymentMethods()
        ]);
    }

    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->oppwaService->getSupportedCurrencies()
        ]);
    }

    /**
     * Get configuration status
     */
    public function getConfigStatus(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->oppwaService->getConfigStatus()
        ]);
    }

    /**
     * Handle OPPWA webhook
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();

        Log::info('OPPWA webhook received', [
            'payload' => $payload,
            'headers' => $request->headers->all()
        ]);

        try {
            $webhookData = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('OPPWA webhook: Invalid JSON payload', [
                    'payload' => $payload,
                    'json_error' => json_last_error_msg()
                ]);
                return response()->json(['message' => 'Invalid JSON payload'], 400);
            }

            // Log the webhook data for debugging
            Log::info('OPPWA webhook received', [
                'event' => $webhookData['event'] ?? 'unknown',
                'checkout_id' => $webhookData['id'] ?? null,
                'payment_id' => $webhookData['payment_id'] ?? null,
                'status' => $webhookData['status'] ?? null,
                'full_payload' => $webhookData
            ]);

            // Handle webhook through client payment service
            $this->clientPaymentService->handleGatewayWebhook('oppwa', $webhookData);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('OPPWA webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }
}
