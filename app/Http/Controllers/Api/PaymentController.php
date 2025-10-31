<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Generate payment link for checkout
     */
    public function generatePaymentLink(Request $request): JsonResponse
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
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'client_order_id' => 'nullable|string|max:100',
            'return_url' => 'nullable|url|max:500',
            'cancel_url' => 'nullable|url|max:500',
            'metadata' => 'nullable|array',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Validate client can make payment
            if (!$client->isCurrencyAllowed($request->currency)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Currency not allowed for your account',
                    'error_code' => 'CURRENCY_NOT_ALLOWED'
                ], 400);
            }

            if (!$client->isWithinLimits($request->amount)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount exceeds your limits',
                    'error_code' => 'LIMIT_EXCEEDED'
                ], 400);
            }

            // Generate transaction and checkout session IDs
            $transactionId = PaymentTransaction::generateTransactionId();
            $checkoutSessionId = PaymentTransaction::generateCheckoutSessionId();

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'api_client_id' => $client->id,
                'transaction_id' => $transactionId,
                'checkout_session_id' => $checkoutSessionId,
                'client_order_id' => $request->client_order_id,
                'amount' => $request->amount,
                'currency' => strtoupper($request->currency),
                'description' => $request->description ?? 'Payment via API',
                'customer_email' => $request->customer_email,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'return_url' => $request->return_url ??null,
                'cancel_url' => $request->cancel_url ?? route('payment.failed',["transaction_id"=>$transactionId]),
                'checkout_url' => route('payment.checkout', ['session' => $checkoutSessionId]),
                'available_gateways' => ['credit_card', 'crypto'], // Default options
                'status' => PaymentTransaction::STATUS_CHECKOUT_PENDING,
                'stage' => PaymentTransaction::STAGE_CHECKOUT,
                'metadata' => $request->metadata,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment link generated successfully',
                'data' => [
                    'transaction_id' => $transaction->transaction_id,
                    'checkout_session_id' => $transaction->checkout_session_id,
                    'checkout_url' => $transaction->checkout_url,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'status' => $transaction->status,
                    'expires_at' => now()->addHours(24)->toISOString(), // 24 hour expiry
                    'created_at' => $transaction->created_at->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payment link',
                'error_code' => 'INTERNAL_ERROR'
            ], 500);
        }
    }

    /**
     * Get payment details by transaction ID
     */
    public function getPaymentDetails(Request $request, string $transactionId): JsonResponse
    {
        $client = $request->get('api_client');

        if (!$client instanceof ApiClient) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API client',
                'error_code' => 'INVALID_CLIENT'
            ], 401);
        }

        $transaction = PaymentTransaction::where('transaction_id', $transactionId)
            ->where('api_client_id', $client->id)
            ->first();

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
     * Check payment status for crypto checkout page (public endpoint)
     */
    public function checkPaymentStatus(string $transactionId): JsonResponse
    {
        $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
                'error_code' => 'TRANSACTION_NOT_FOUND'
            ], 404);
        }

        // Check if transaction is expired (24 hours)
        if ($transaction->created_at->addHours(24)->isPast()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'transaction_id' => $transaction->transaction_id,
                    'status' => 'expired',
                    'message' => 'Payment session has expired'
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'transaction_id' => $transaction->transaction_id,
                'status' => strtolower($transaction->status),
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'gateway_name' => $transaction->gateway_name,
                'created_at' => $transaction->created_at->toISOString(),
                'updated_at' => $transaction->updated_at->toISOString(),
            ]
        ]);
    }
}
