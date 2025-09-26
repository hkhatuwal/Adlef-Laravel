<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Client\ClientLoginController;
use App\Http\Controllers\Client\CryptoWalletController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\UserController;

Route::get('install', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
    echo 'ok';
});
Route::get('/run-scheduler', function () {
    Log::info('Scheduler executed at ' . now());
    Artisan::call('schedule:run');
    return 'Scheduler executed at ' . now();
});
Route::get('storage', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

Route::group(['as' => 'frontend.'], function () {
    Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [\App\Http\Controllers\Frontend\AboutController::class, 'index'])->name('about');
    Route::get('/solutions', [\App\Http\Controllers\Frontend\SolutionsController::class, 'index'])->name('solutions');
    Route::get('/news-insights', [\App\Http\Controllers\Frontend\NewsInsightsController::class, 'index'])->name('news-insights');
    Route::get('/open-trust-apis', [\App\Http\Controllers\Frontend\OpentrustApisPageController::class, 'index'])->name('opentrust-apis-page');
    Route::get('/careers', [\App\Http\Controllers\Frontend\CareerController::class, 'index'])->name('careers');
    Route::get('/contact-us/business-enquiry', [\App\Http\Controllers\Frontend\ContactUsController::class, 'businessEnquiry'])->name('contact-us.business-enquiry');
    Route::get('/contact-us', [\App\Http\Controllers\Frontend\ContactUsController::class, 'contactUs'])->name('contact-us');
    Route::post('/contact-us/business-enquiry', [\App\Http\Controllers\Frontend\ContactUsController::class, 'store'])->name('contact-us.business-enquiry');
    Route::get('/privacy-policy', [\App\Http\Controllers\Frontend\PrivacyPolicyController::class, 'index'])->name('privacy-policy');
    Route::get('/cookie-policy', [\App\Http\Controllers\Frontend\CookiePolicyController::class, 'index'])->name('cookie-policy');
    Route::get('/terms-of-use', [\App\Http\Controllers\Frontend\TermsOfUseController::class, 'index'])->name('terms-of-use');
    Route::get('/faq', [\App\Http\Controllers\Frontend\FAQController::class, 'index'])->name('faq');
    Route::get('/api-documentation', [\App\Http\Controllers\Frontend\ApiDocumentationController::class, 'index'])->name('api-documentation');

    // Help Center Routes

    // Authenticated Help Center Routes
    Route::middleware('auth')->group(function () {
        Route::get('/help-center', [\App\Http\Controllers\Frontend\HelpCenterController::class, 'index'])->name('help-center');
        Route::post('/help-center', [\App\Http\Controllers\Frontend\HelpCenterController::class, 'store'])->name('help-center.store');
        Route::get('/help-center/my-requests', [\App\Http\Controllers\Frontend\HelpCenterController::class, 'myRequests'])->name('help-center.my-requests');
        Route::get('/help-center/{helpRequest}', [\App\Http\Controllers\Frontend\HelpCenterController::class, 'show'])->name('help-center.show');
    });
});

// Client Routes
Route::group(['prefix' => 'client', 'as' => 'client.', 'middleware' => ['auth:client']], function () {
    Route::post('/crypto-wallet', [\App\Http\Controllers\Client\CryptoWalletController::class, 'store'])->name('crypto-wallet.store');
});

Route::get('/users/{user}/commissions', [UserController::class, 'commissions'])->name('users.commissions');
Route::post('/users/{user}/commissions', [UserController::class, 'updateCommissions'])->name('users.commissions.update');

Route::get('/users/{user}/accounts', [UserController::class, 'accounts'])->name('users.accounts');

// Payment Checkout Routes (No authentication required for public checkout)
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/checkout/{session}', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/{session}/select', [\App\Http\Controllers\CheckoutController::class, 'selectPaymentMethod'])->name('checkout.select');
    Route::get('/crypto/{orderId}', [\App\Http\Controllers\CheckoutController::class, 'cryptoCheckout'])->name('crypto.checkout');
    Route::get('/checkout/status/{transactionId}', [\App\Http\Controllers\CheckoutController::class, 'checkPaymentStatus'])->name('checkout.status');
    Route::any('/success', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('success');
    Route::any('/failed', [\App\Http\Controllers\CheckoutController::class, 'failed'])->name('failed');
    Route::get('/error', [\App\Http\Controllers\CheckoutController::class, 'showError'])->name('error');
});



