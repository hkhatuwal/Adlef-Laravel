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
     * Supported webhook events:
     * - AUTHORISED: Payment has been authorized
     * - DECLINED: Authorization declined by issuing bank
     * - APM_PAYMENT_ACCEPTED: APM payment success response
     * - AUTHORISATION_FAILED: Authorization process failed
     * - FULL_AUTH_REVERSED: Authorization reversed
     * - FULL_AUTH_REVERSAL_FAILED: Authorization reversal failed
     * - PURCHASED: Purchase process succeeded
     * - PURCHASE_DECLINED: Purchase process declined
     * - PURCHASE_FAILED: Purchase process failed
     * - PURCHASE_REVERSED: Previous purchase reversed
     * - PURCHASE_REVERSAL_FAILED: Purchase reversal failed
     * - CAPTURED: Authorized payment captured in full
     * - CAPTURE_FAILED: Capture process failed
     * - CAPTURE_VOIDED: Previous capture cancelled/voided
     * - CAPTURE_VOID_FAILED: Capture void request failed
     * - CANCELLATION_REQUESTED: APM payment cancellation requested
     * - CANCELLATION_FAILED: APM payment cancellation failed
     * - CANCELLED: APM payment cancellation succeeded
     * - ORDER_CLOSED: Order marked as closed
     * - PARTIALLY_CAPTURED: Authorized payment partially captured
     * - PARTIAL_CAPTURE_FAILED: Partial capture request failed
     * - REFUNDED: Captured payment refunded
     * - REFUND_FAILED: Refund request failed
     * - PARTIALLY_REFUNDED: Captured payment partially refunded
     * - REFUND_REQUESTED: Refund requested to APM system
     * - REFUND_REQUEST_FAILED: Refund request to APM failed
     * - PARTIAL_REFUND_FAILED: Partial refund request failed
     * - PARTIAL_REFUND_REQUEST_FAILED: Partial refund request to APM failed
     * - PARTIAL_REFUND_REQUESTED: Partial refund requested to APM system
     * - REFUND_VOIDED: Refund cancelled/voided
     * - REFUND_VOID_FAILED: Refund void request failed
     * - REFUND_VOID_REQUESTED: Cancel refund requested to APM system
     * - GATEWAY_RISK_PRE_AUTH_REJECTED: Rejected by pre-authorization risk rules
     * - PRE_AUTH_FRAUD_CHECK_REJECTED: Rejected by pre-authorization fraud screening
     * - POST_AUTH_FRAUD_CHECK_REJECTED: Rejected by post-authorization fraud screening
     * - POST_AUTH_FRAUD_CHECK_REVIEW: Post auth check pending
     * - POST_AUTH_FRAUD_CHECK_ACCEPTED: Post auth check success
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();

        Log::info($payload);

        try {
            $data = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('N-Genius webhook: Invalid JSON payload', [
                    'payload' => $payload,
                    'json_error' => json_last_error_msg()
                ]);
                return response()->json(['message' => 'Invalid JSON payload'], 400);
            }

            // Log the webhook data for debugging
            Log::info('N-Genius webhook received', [
                'event' => $data['event'] ?? 'unknown',
                'order_id' => $data['order']['id'] ?? null,
                'order_reference' => $data['order']['reference'] ?? null,
                'transaction_id' => $data['transaction']['id'] ?? null,
                'transaction_state' => $data['transaction']['state'] ?? null,
                'full_payload' => $data
            ]);

            // Handle webhook through client payment service
            $this->clientPaymentService->handleGatewayWebhook('ngenius', $data);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('N-Genius webhook processing failed', [
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
