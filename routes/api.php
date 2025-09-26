<?php

use App\Http\Controllers\Api\AssetTransferVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('/transfers/verify', [AssetTransferVerificationController::class, 'verifyByReference']);

// Payment Gateway API routes with client authentication
Route::middleware(['api_key'])->prefix('v1/payment')->group(function () {
    // New payment generation endpoint
    Route::post('/generate', [\App\Http\Controllers\Api\PaymentController::class, 'generatePaymentLink']);
    Route::any('/details/{transactionId}', [\App\Http\Controllers\Api\PaymentController::class, 'getPaymentDetails']);

    // Payop routes
//    Route::prefix('payop')->group(function () {
//        Route::post('/create', [\App\Http\Controllers\Api\PaymentGateway\PayopController::class, 'createPayment']);
//        Route::get('/payment-methods', [\App\Http\Controllers\Api\PaymentGateway\PayopController::class, 'getPaymentMethods']);
//        Route::get('/currencies', [\App\Http\Controllers\Api\PaymentGateway\PayopController::class, 'getSupportedCurrencies']);
//        Route::get('/config', [\App\Http\Controllers\Api\PaymentGateway\PayopController::class, 'getConfigStatus']);
//
//        // Transaction management
//        Route::get('/transactions', [\App\Http\Controllers\Api\PaymentGateway\PayopController::class, 'getTransactions']);
//        Route::get('/transactions/{transactionId}', [\App\Http\Controllers\Api\PaymentGateway\PayopController::class, 'getTransaction']);
//    });
});

// Public payment status check (no authentication required for checkout page)
Route::any('/payment/status/{transactionId}', [\App\Http\Controllers\Api\PaymentController::class, 'checkPaymentStatus']);

// Webhook routes (no authentication required as they come from payment gateways)
Route::prefix('webhooks')->group(function () {
    Route::any('/payop', [\App\Http\Controllers\Api\PaymentGateway\PayopController::class, 'handleWebhook']);
    Route::any('/paydo', [\App\Http\Controllers\Api\PaymentGateway\PaydoController::class, 'handleWebhook']);
    Route::any('/trongrid', [\App\Http\Controllers\Api\PaymentGateway\TronGridController::class, 'handleWebhook']);
    Route::any('/ngenius', [\App\Http\Controllers\Api\PaymentGateway\NgeniusController::class, 'handleWebhook']);
    Route::any('/pay4work', [\App\Http\Controllers\Api\PaymentGateway\Pay4WorkController::class, 'handleWebhook']);
});
