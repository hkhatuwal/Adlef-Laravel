<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?? $request->get('api_key');
        $secretKey = $request->header('X-Secret-Key') ?? $request->get('secret_key');

        if (!$apiKey || !$secretKey) {
            return $this->unauthorizedResponse('API Key and Secret Key are required');
        }

        $client = ApiClient::verifyCredentials($apiKey, $secretKey);

        if (!$client) {
            return $this->unauthorizedResponse('Invalid API credentials');
        }

        if ($client->isExpired()) {
            return $this->unauthorizedResponse('API credentials have expired');
        }

        // Check IP restriction
        $clientIp = $request->ip();
        if (!$client->isIpAllowed($clientIp)) {
            return $this->forbiddenResponse('IP address not allowed');
        }

        // Add client to request for use in controllers
        $request->merge(['api_client' => $client]);

        return $next($request);
    }

    /**
     * Return unauthorized response
     */
    private function unauthorizedResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => 'UNAUTHORIZED'
        ], 401);
    }

    /**
     * Return forbidden response
     */
    private function forbiddenResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => 'FORBIDDEN'
        ], 403);
    }
}
