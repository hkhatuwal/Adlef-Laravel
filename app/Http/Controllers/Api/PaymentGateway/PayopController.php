<?php

namespace App\Http\Controllers\Api\PaymentGateway;

use App\Contracts\PaymentGatewayFactory;
use App\Http\Controllers\Controller;
use App\Services\PaymentGateway\PayopPaymentGateway;
use App\Services\PaymentService;
use App\Services\ClientPaymentService;
use App\Models\ApiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PayopController extends Controller
{
    protected PaymentService $paymentService;
    protected PaymentGatewayFactory $gatewayFactory;
    protected ClientPaymentService $clientPaymentService;

    public function __construct(
        PaymentService $paymentService,
        PaymentGatewayFactory $gatewayFactory,
        ClientPaymentService $clientPaymentService
    ) {
        $this->paymentService = $paymentService;
        $this->gatewayFactory = $gatewayFactory;
        $this->clientPaymentService = $clientPaymentService;
    }


    /**
     * Create a payment page for Payop (Client API)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'description' => 'sometimes|string|max:255',
            'customer_email' => 'required|email',
            'customer_name' => 'sometimes|string|max:255',
            'customer_phone' => 'sometimes|string|max:20',
            'order_id' => 'sometimes|string|max:100',
            'language' => 'sometimes|string|in:en,ru,es,de,fr',
            'return_url' => 'required|url',
            'cancel_url' => 'required|url',
            'metadata' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the authenticated API client
        $client = $request->get('api_client');

        if (!$client instanceof ApiClient) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API client',
                'error_code' => 'INVALID_CLIENT'
            ], 401);
        }

        $paymentData = [
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency),
            'description' => $request->description ,
            'customer_email' => $request->customer_email ?? '',
            'customer_name' => $request->customer_name ?? '',
            'customer_phone' => $request->customer_phone ?? '',
            'order_id' => $request->order_id,
            'language' => $request->language ?? 'en',
            'return_url' => $request->return_url,
            'cancel_url' => $request->cancel_url,
            'metadata' => $request->metadata ?? [],
        ];

        $result = $this->clientPaymentService->createPayment($client, $paymentData);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 400);
    }

    /**
     * Get available payment methods for a specific currency
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPaymentMethods(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|size:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $gateway = $this->gatewayFactory->create('payop');

            if (!($gateway instanceof PayopPaymentGateway)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payop gateway not available'
                ], 500);
            }

            $methods = $gateway->getAvailablePaymentMethods($request->currency);

            return response()->json([
                'success' => true,
                'currency' => strtoupper($request->currency),
                'payment_methods' => $methods
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get Payop payment methods', [
                'error' => $e->getMessage(),
                'currency' => $request->currency
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment methods',
                'error_code' => 'PAYMENT_METHODS_ERROR'
            ], 500);
        }
    }

    /**
     * Get supported currencies
     *
     * @return JsonResponse
     */
    public function getSupportedCurrencies(): JsonResponse
    {
        try {
            $gateway = $this->gatewayFactory->create('payop');

            if (!($gateway instanceof PayopPaymentGateway)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payop gateway not available'
                ], 500);
            }

            $currencies = $gateway->getSupportedCurrencies();

            return response()->json([
                'success' => true,
                'currencies' => $currencies
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get Payop supported currencies', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve supported currencies',
                'error_code' => 'CURRENCIES_ERROR'
            ], 500);
        }
    }

    /**
     * Handle Payop webhook
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Payop-Signature') ?? $request->header('signature');


        try {
            $data = json_decode($payload, true);
            // Handle webhook through client payment service
            $this->clientPaymentService->handleGatewayWebhook('payop', $data);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Payop webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle specific Payop webhook events
     *
     * @param array $data
     * @return void
     */
    protected function handlePayopWebhookEvent(array $data): void
    {
        $eventType = $data['type'] ?? $data['event'] ?? 'unknown';
        $transactionData = $data['data'] ?? $data;

        switch ($eventType) {
            case 'invoice.created':
                Log::info('Payop invoice created', ['transaction_id' => $transactionData['id'] ?? null]);
                break;

            case 'invoice.paid':
            case 'payment.success':
                $this->handlePaymentSuccess($transactionData);
                break;

            case 'invoice.failed':
            case 'payment.failed':
                $this->handlePaymentFailed($transactionData);
                break;

            case 'invoice.refunded':
            case 'refund.success':
                $this->handleRefundSuccess($transactionData);
                break;

            default:
                Log::info('Unhandled Payop webhook event', [
                    'event_type' => $eventType,
                    'data' => $transactionData
                ]);
                break;
        }
    }

    /**
     * Handle successful payment webhook
     *
     * @param array $transactionData
     * @return void
     */
    protected function handlePaymentSuccess(array $transactionData): void
    {
        Log::info('Payop payment successful', [
            'transaction_id' => $transactionData['id'] ?? null,
            'amount' => $transactionData['amount'] ?? null,
            'currency' => $transactionData['currency'] ?? null
        ]);

        // Here you would typically update your database
        // Update order status, send confirmation emails, etc.
    }

    /**
     * Handle failed payment webhook
     *
     * @param array $transactionData
     * @return void
     */
    protected function handlePaymentFailed(array $transactionData): void
    {
        Log::warning('Payop payment failed', [
            'transaction_id' => $transactionData['id'] ?? null,
            'error' => $transactionData['error'] ?? null
        ]);

        // Here you would typically update your database
        // Update order status, send failure notifications, etc.
    }

    /**
     * Handle successful refund webhook
     *
     * @param array $transactionData
     * @return void
     */
    protected function handleRefundSuccess(array $transactionData): void
    {
        Log::info('Payop refund successful', [
            'transaction_id' => $transactionData['id'] ?? null,
            'refund_amount' => $transactionData['amount'] ?? null
        ]);

        // Here you would typically update your database
        // Update refund status, send refund confirmation, etc.
    }

    /**
     * Get transaction details
     *
     * @param Request $request
     * @param string $transactionId
     * @return JsonResponse
     */
    public function getTransaction(Request $request, string $transactionId): JsonResponse
    {
        $client = $request->get('api_client');

        if (!$client instanceof ApiClient) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API client',
                'error_code' => 'INVALID_CLIENT'
            ], 401);
        }

        $transaction = $this->clientPaymentService->getTransactionForClient($client, $transactionId);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
                'error_code' => 'TRANSACTION_NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction->toApiResponse()
        ]);
    }

    /**
     * Get client transactions with pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTransactions(Request $request): JsonResponse
    {
        $client = $request->get('api_client');

        if (!$client instanceof ApiClient) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API client',
                'error_code' => 'INVALID_CLIENT'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|string|in:pending,processing,completed,failed,cancelled,refunded',
            'currency' => 'sometimes|string|size:3',
            'from_date' => 'sometimes|date',
            'to_date' => 'sometimes|date|after_or_equal:from_date',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $filters = $request->only(['status', 'currency', 'from_date', 'to_date']);
        $perPage = $request->get('per_page', 15);

        $result = $this->clientPaymentService->getClientTransactions($client, $filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => array_map(function ($transaction) {
                return $transaction->toApiResponse();
            }, $result['data']),
            'pagination' => $result['pagination']
        ]);
    }

    /**
     * Get Payop configuration status
     *
     * @return JsonResponse
     */
    public function getConfigStatus(): JsonResponse
    {
        try {
            $config = config('payment.gateways.payop');

            $status = [
                'configured' => !empty($config['public_key']) && !empty($config['secret_key']),
                'sandbox_mode' => $config['sandbox'] ?? true,
                'webhook_configured' => !empty($config['webhook_secret']),
                'supported_features' => config('payment.features.payop', []),
                'supported_currencies' => config('payment.currencies.payop', []),
                'transaction_limits' => config('payment.limits.payop', []),
            ];

            return response()->json([
                'success' => true,
                'payop_status' => $status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve configuration status',
                'error_code' => 'CONFIG_ERROR'
            ], 500);
        }
    }
}
