<?php

namespace App\Http\Controllers\Api\PaymentGateway;

use App\Contracts\PaymentGatewayFactory;
use App\Http\Controllers\Controller;
use App\Services\PaymentGateway\PaydoPaymentGateway;
use App\Services\PaymentService;
use App\Services\ClientPaymentService;
use App\Models\ApiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaydoController extends Controller
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
     * Create a payment page for Paydo (Client API)
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
            'description' => $request->description,
            'customer_email' => $request->customer_email ?? '',
            'customer_name' => $request->customer_name ?? '',
            'customer_phone' => $request->customer_phone ?? '',
            'order_id' => $request->order_id,
            'language' => $request->language ?? 'en',
            'return_url' => $request->return_url,
            'cancel_url' => $request->cancel_url,
            'metadata' => $request->metadata ?? [],
        ];

        $result = $this->clientPaymentService->createPayment($client, $paymentData, 'paydo');

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
            /** @var PaydoPaymentGateway $gateway */
            $gateway = $this->gatewayFactory->create('paydo');
            $paymentMethods = $gateway->getAvailablePaymentMethods($request->currency);

            return response()->json([
                'success' => true,
                'message' => 'Payment methods retrieved successfully',
                'data' => [
                    'currency' => strtoupper($request->currency),
                    'payment_methods' => $paymentMethods
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get Paydo payment methods', [
                'error' => $e->getMessage(),
                'currency' => $request->currency
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment methods',
                'error_code' => 'PAYDO_ERROR'
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
            /** @var PaydoPaymentGateway $gateway */
            $gateway = $this->gatewayFactory->create('paydo');
            $currencies = $gateway->getSupportedCurrencies();

            return response()->json([
                'success' => true,
                'message' => 'Supported currencies retrieved successfully',
                'data' => [
                    'currencies' => $currencies
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get Paydo supported currencies', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve supported currencies',
                'error_code' => 'PAYDO_ERROR'
            ], 500);
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
            /** @var PaydoPaymentGateway $gateway */
            $gateway = $this->gatewayFactory->create('paydo');
            $response = $gateway->getPaymentStatus($transactionId);

            if ($response->isSuccessful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment status retrieved successfully',
                    'data' => [
                        'transaction_id' => $response->getTransactionId(),
                        'status' => $response->getStatus(),
                        'amount' => $response->getAmount(),
                        'currency' => $response->getCurrency(),
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response->getMessage(),
                'error_code' => $response->getErrorCode()
            ], 400);

        } catch (\Exception $e) {
            Log::error('Failed to get Paydo payment status', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment status',
                'error_code' => 'PAYDO_ERROR'
            ], 500);
        }
    }

    /**
     * Handle Paydo webhook
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();


        try {
            $data = json_decode($payload, true);
            // Handle webhook through client payment service
            $this->clientPaymentService->handleGatewayWebhook('paydo', $data);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Payop webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

}
