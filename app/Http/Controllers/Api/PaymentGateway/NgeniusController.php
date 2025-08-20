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

class NgeniusController extends Controller
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
     * Create a payment page for N-Genius (Client API)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3|in:AED,USD,EUR,GBP,SAR',
            'description' => 'sometimes|string|max:255',
            'customer_email' => 'required|email',
            'customer_name' => 'sometimes|string|max:255',
            'customer_phone' => 'sometimes|string|max:20',
            'order_id' => 'sometimes|string|max:100',
            'language' => 'sometimes|string|in:en,ar',
            'return_url' => 'required|url',
            'cancel_url' => 'required|url',
            'action' => 'sometimes|string|in:PURCHASE,AUTH,SALE',
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
                'description' => $request->description ?? 'Payment via N-Genius',
                'customer_email' => $request->customer_email,
                'customer_name' => $request->customer_name ?? '',
                'customer_phone' => $request->customer_phone ?? '',
                'order_id' => $request->order_id ?? 'order_' . time() . '_' . rand(1000, 9999),
                'language' => $request->language ?? 'en',
                'return_url' => $request->return_url,
                'cancel_url' => $request->cancel_url,
                'action' => $request->action ?? 'PURCHASE',
                'metadata' => $request->metadata ?? [],
            ];

            // Create payment using the client payment service
            $result = $this->clientPaymentService->createPayment($apiClient, $paymentData, 'ngenius');

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'N-Genius payment created successfully',
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
                'message' => $result['message'] ?? 'Failed to create N-Genius payment',
                'error_code' => $result['error_code'] ?? 'NGENIUS_CREATE_ERROR'
            ], 400);

        } catch (\Exception $e) {
            Log::error('N-Genius payment creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error while creating payment',
                'error_code' => 'NGENIUS_INTERNAL_ERROR'
            ], 500);
        }
    }

    /**
     * Handle N-Genius webhook
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function webhook(Request $request): JsonResponse
    {
        try {
            Log::info('N-Genius webhook received', [
                'headers' => $request->headers->all(),
                'payload' => $request->all()
            ]);

            // Get webhook payload
            $payload = $request->getContent();
            $webhookData = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('N-Genius webhook: Invalid JSON payload');
                return response()->json(['error' => 'Invalid JSON'], 400);
            }

            // Process webhook using client payment service
            $this->clientPaymentService->handleGatewayWebhook('ngenius', $webhookData);
            $result = ['success' => true];

            if ($result['success']) {
                return response()->json(['status' => 'success'], 200);
            }

            return response()->json(['error' => $result['message'] ?? 'Webhook processing failed'], 400);

        } catch (\Exception $e) {
            Log::error('N-Genius webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
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
            $gateway = $this->gatewayFactory->create('ngenius');
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
                    'error_code' => $gatewayResponse->getData()['error_code'] ?? 'NGENIUS_ERROR'
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
                'error_code' => $result['error_code'] ?? 'NGENIUS_STATUS_ERROR'
            ], 400);

        } catch (\Exception $e) {
            Log::error('N-Genius payment status check failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error while checking payment status',
                'error_code' => 'NGENIUS_INTERNAL_ERROR'
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
            $gateway = $this->gatewayFactory->create('ngenius');
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
                    'error_code' => $gatewayResponse->getData()['error_code'] ?? 'NGENIUS_REFUND_ERROR'
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
                'error_code' => $result['error_code'] ?? 'NGENIUS_REFUND_ERROR'
            ], 400);

        } catch (\Exception $e) {
            Log::error('N-Genius refund processing failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId,
                'amount' => $request->amount
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error while processing refund',
                'error_code' => 'NGENIUS_INTERNAL_ERROR'
            ], 500);
        }
    }
}
