<?php

namespace App\Http\Controllers\Api\PaymentGateway;

use App\Contracts\PaymentGatewayFactory;
use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Services\ClientPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Pay4WorkController extends Controller
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
     * Create a payment page for Pay4Work (Client API)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3|in:AED,INR,USD',
            'description' => 'sometimes|string|max:255',
            'customer_email' => 'required|email',
            'customer_name' => 'sometimes|string|max:255',
            'customer_phone' => 'sometimes|string|max:20',
            'order_id' => 'sometimes|string|max:100',
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

        try {
            // Get API client from middleware or authentication
            $apiClient = $request->attributes->get('api_client');
            if (!$apiClient) {
                return response()->json([
                    'success' => false,
                    'message' => 'API client not found'
                ], 401);
            }

            // Prepare payment data
            $paymentData = [
                'amount' => $request->amount,
                'currency' => strtoupper($request->currency),
                'description' => $request->description ?? 'Payment via Pay4Work',
                'customer_email' => $request->customer_email,
                'customer_name' => $request->customer_name ?? '',
                'customer_phone' => $request->customer_phone ?? '',
                'order_id' => $request->order_id ?? 'order_' . time() . '_' . rand(1000, 9999),
                'return_url' => $request->return_url,
                'cancel_url' => $request->cancel_url,
                'metadata' => $request->metadata ?? [],
            ];

            // Create payment using the client payment service
            $result = $this->clientPaymentService->createPayment($apiClient, $paymentData, 'pay4work');

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pay4Work payment created successfully',
                    'data' => [
                        'transaction_id' => $result['transaction_id'],
                        'payment_url' => $result['payment_url'],
                        'status' => $result['status'],
                        'amount' => $result['amount'],
                        'currency' => $result['currency']
                    ]
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to create Pay4Work payment',
                'error_code' => $result['error_code'] ?? 'PAY4WORK_CREATE_ERROR'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Pay4Work payment creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error while creating payment',
                'error_code' => 'PAY4WORK_INTERNAL_ERROR'
            ], 500);
        }
    }

    /**
     * Handle Pay4Work webhook
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();

        Log::info('Pay4Work webhook received', [
            'payload' => $payload,
            'headers' => $request->headers->all()
        ]);

        try {
            // Parse the webhook data
            $webhookData = $request->all();

            // Validate webhook signature
            $gateway = $this->gatewayFactory->create('pay4work');
            $isValid = $gateway->verifyWebhookSignature(
                $payload,
                $request->header('X-Signature', ''),
                config('payment.gateways.pay4work.webhook_secret', '')
            );

            if (!$isValid) {
                Log::warning('Pay4Work webhook signature validation failed', [
                    'payload' => $payload,
                    'signature' => $request->header('X-Signature')
                ]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            // Handle webhook through client payment service
            $this->clientPaymentService->handleGatewayWebhook('pay4work', $webhookData);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Pay4Work webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Get payment status
     *
     * @param Request $request
     * @param string $transactionId
     * @return JsonResponse
     */
    public function getPaymentStatus(Request $request, string $transactionId): JsonResponse
    {
        try {
            // Get API client from middleware or authentication
            $apiClient = $request->attributes->get('api_client');
            if (!$apiClient) {
                return response()->json([
                    'success' => false,
                    'message' => 'API client not found'
                ], 401);
            }

            // Get payment status using the gateway directly
            $gateway = $this->gatewayFactory->create('pay4work');
            $gatewayResponse = $gateway->getPaymentStatus($transactionId);

            if ($gatewayResponse->isSuccessful()) {
                $result = [
                    'success' => true,
                    'transaction_id' => $gatewayResponse->getTransactionId(),
                    'status' => $gatewayResponse->getStatus(),
                    'amount' => $gatewayResponse->getAmount(),
                    'currency' => $gatewayResponse->getCurrency(),
                    'message' => $gatewayResponse->getMessage()
                ];
            } else {
                $result = [
                    'success' => false,
                    'message' => $gatewayResponse->getMessage(),
                    'error_code' => $gatewayResponse->getData()['error_code'] ?? 'PAY4WORK_ERROR'
                ];
            }

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'transaction_id' => $result['transaction_id'],
                        'status' => $result['status'],
                        'amount' => $result['amount'],
                        'currency' => $result['currency'],
                        'message' => $result['message']
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to get payment status',
                'error_code' => $result['error_code'] ?? 'PAY4WORK_STATUS_ERROR'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Pay4Work payment status check failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error while checking payment status',
                'error_code' => 'PAY4WORK_INTERNAL_ERROR'
            ], 500);
        }
    }

    /**
     * Process refund
     *
     * @param Request $request
     * @param string $transactionId
     * @return JsonResponse
     */
    public function refund(Request $request, string $transactionId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'reason' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get API client from middleware or authentication
            $apiClient = $request->attributes->get('api_client');
            if (!$apiClient) {
                return response()->json([
                    'success' => false,
                    'message' => 'API client not found'
                ], 401);
            }

            // Process refund using the gateway directly
            $gateway = $this->gatewayFactory->create('pay4work');
            $gatewayResponse = $gateway->refundPayment(
                $transactionId,
                $request->amount,
                [
                    'currency' => strtoupper($request->currency),
                    'reason' => $request->reason ?? 'Refund requested',
                ]
            );

            if ($gatewayResponse->isSuccessful()) {
                $result = [
                    'success' => true,
                    'transaction_id' => $gatewayResponse->getTransactionId(),
                    'status' => $gatewayResponse->getStatus(),
                    'amount' => $gatewayResponse->getAmount(),
                    'currency' => $gatewayResponse->getCurrency()
                ];
            } else {
                $result = [
                    'success' => false,
                    'message' => $gatewayResponse->getMessage(),
                    'error_code' => $gatewayResponse->getData()['error_code'] ?? 'PAY4WORK_REFUND_ERROR'
                ];
            }

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Refund processed successfully',
                    'data' => [
                        'transaction_id' => $result['transaction_id'],
                        'status' => $result['status'],
                        'amount' => $result['amount'],
                        'currency' => $result['currency']
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to process refund',
                'error_code' => $result['error_code'] ?? 'PAY4WORK_REFUND_ERROR'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Pay4Work refund processing failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId,
                'amount' => $request->amount
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error while processing refund',
                'error_code' => 'PAY4WORK_INTERNAL_ERROR'
            ], 500);
        }
    }
}
