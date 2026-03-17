<?php

namespace App\Http\Controllers\Api\PaymentGateway;

use App\Contracts\PaymentGatewayFactory;
use App\Http\Controllers\Controller;
use App\Services\ClientPaymentService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TapController extends Controller
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
     * Handle Tap webhook
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();

        Log::info('TAP webhook received', [
            'payload' => $payload,
            'headers' => $request->headers->all()
        ]);

        try {
            $webhookData = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('TAP webhook: Invalid JSON payload', [
                    'payload' => $payload,
                    'json_error' => json_last_error_msg()
                ]);
                return response()->json(['message' => 'Invalid JSON payload'], 400);
            }

            // Validate webhook signature (if configured)
            $secret = config('payment.gateways.tap.webhook_secret', '');
            if (!empty($secret)) {
                $signature = $request->header('Tap-Signature')
                    ?? $request->header('X-Signature')
                    ?? $request->header('X-Tap-Signature')
                    ?? '';

                $gateway = $this->gatewayFactory->create('tap');
                $isValid = $gateway->verifyWebhookSignature($payload, $signature, $secret);
                if (!$isValid) {
                    Log::warning('TAP webhook signature validation failed', [
                        'signature' => $signature
                    ]);
                    return response()->json(['message' => 'Invalid signature'], 400);
                }
            }

            // Handle webhook through client payment service
            $this->clientPaymentService->handleGatewayWebhook('tap', $webhookData);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('TAP webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }
}



