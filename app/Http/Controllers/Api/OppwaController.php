<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OppwaService;
use App\Models\OppwaTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        try {
            $payload = $request->getContent();
            $signature = $request->header('X-OPPWA-Signature', '');
            
            Log::info('OPPWA webhook received', [
                'payload' => $payload,
                'signature' => $signature,
                'headers' => $request->headers->all()
            ]);

            // Verify webhook signature if configured
            $webhookSecret = config('services.oppwa.webhook_secret');
            if ($webhookSecret && !$this->oppwaService->verifyWebhookSignature($payload, $signature, $webhookSecret)) {
                Log::warning('OPPWA webhook signature verification failed');
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $webhookData = json_decode($payload, true);
            
            if (!$webhookData) {
                Log::error('OPPWA webhook payload is not valid JSON');
                return response()->json(['error' => 'Invalid payload'], 400);
            }

            // Parse webhook data
            $parsedData = $this->oppwaService->parseWebhookData($webhookData);
            
            // Find transaction by OPPWA checkout ID or payment ID
            $transaction = null;
            if (isset($webhookData['id'])) {
                $transaction = OppwaTransaction::where('oppwa_checkout_id', $webhookData['id'])
                    ->orWhere('oppwa_payment_id', $webhookData['id'])
                    ->first();
            }

            if (!$transaction) {
                Log::warning('OPPWA webhook: Transaction not found', ['webhook_data' => $webhookData]);
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Update transaction status
            $isSuccessful = OppwaTransaction::isOppwaResponseSuccessful($webhookData);
            $newStatus = $isSuccessful ? OppwaTransaction::STATUS_COMPLETED : OppwaTransaction::STATUS_FAILED;
            
            $transaction->updateStatus($newStatus, $webhookData);

            Log::info('OPPWA webhook processed successfully', [
                'transaction_id' => $transaction->transaction_id,
                'status' => $newStatus
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('OPPWA webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
