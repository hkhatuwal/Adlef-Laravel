<?php

use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\AssetTransferController;
use App\Http\Controllers\Client\ClientLoginController;
use App\Http\Controllers\Client\ClientLogoutController;
use App\Http\Controllers\Client\ClientRegistrationController;
use App\Http\Controllers\Client\CryptoWalletController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\DocumentController;
use App\Http\Controllers\Client\OtcController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ActivityController;
use App\Http\Controllers\Client\NotificationController;

/*Client Auth Routes*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [ClientLoginController::class, 'showLoginForm'])->name('client-login');
    Route::post('/login', [ClientLoginController::class, 'login'])->name('auth.login');

    Route::get('/register', [ClientRegistrationController::class, 'showRegistrationForm'])->name('client-registration');
    Route::post('/register/save', [ClientRegistrationController::class, 'saveRegistrationDetails'])->name('client-registration.save');
});
Route::middleware([\App\Http\Middleware\ClientMiddleware::class, \Illuminate\Auth\Middleware\Authenticate::class])->name('client.')->group(function ($app) {

    // API Client Management for authenticated users
    Route::resource('api-clients', \App\Http\Controllers\Client\ApiClientController::class);
    Route::post('api-clients/{apiClient}/regenerate-credentials', [\App\Http\Controllers\Client\ApiClientController::class, 'regenerateCredentials'])
        ->name('api-clients.regenerate-credentials');
    Route::post('api-clients/{apiClient}/toggle-sandbox', [\App\Http\Controllers\Client\ApiClientController::class, 'toggleSandbox'])
        ->name('api-clients.toggle-sandbox');
    Route::get('api-clients/{apiClient}/statistics', [\App\Http\Controllers\Client\ApiClientController::class, 'statistics'])
        ->name('api-clients.statistics');



    Route::middleware([\App\Http\Middleware\ClientVerifyMiddleware::class])->group(function ($app) {
        Route::get('/logout', [ClientLogoutController::class, 'logout'])->name('client-logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/holdings', [DashboardController::class, 'holdings'])->name('holdings');
        Route::get('/activity', [DashboardController::class, 'activity'])->name('activity');

        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Asset Transfer Routes
        Route::get('/transfer', [AssetTransferController::class, 'index'])->name('transfer');
        Route::get('/transfer/in', [AssetTransferController::class, 'transferIn'])->name('transfer.in');
        Route::get('/transfer/out', [AssetTransferController::class, 'transferOut'])->name('transfer.out');
        Route::get('/transfer/show/{transfer}', [AssetTransferController::class, 'show'])->name('transfer.show');
        Route::post('/transfer/transfer/in', [AssetTransferController::class, 'storeTransferIn'])->name('transfer.in.post');
        Route::post('/transfer/transfer/out', [AssetTransferController::class, 'storeTransferOut'])->name('transfer.out.post');
        Route::post('/transfer/calculate-fee', [AssetTransferController::class, 'calculateFee'])->name('client.transfer.calculate-fee');
        Route::post('/transfer/{id}/request-invoice', [AssetTransferController::class, 'requestInvoice'])->name('transfer.request-invoice');
        // OTC Exchange Routes
        Route::prefix('otc')->name('otc.')->group(function () {
            Route::get('/', [OtcController::class, 'index'])->name('index');
            Route::post('/calculate', [OtcController::class, 'calculate'])->name('calculate');
            Route::post('/is-exchange-possible', [OtcController::class, 'isExchangePossible'])->name('is-exchange-possible');
            Route::post('/confirm', [OtcController::class, 'confirmExchange'])->name('confirm');
            Route::get('/success/{id}', [OtcController::class, 'showSuccess'])->name('success');
            Route::get('/show/{id}', [OtcController::class, 'show'])->name('show');
        });

        // Account Management Routes
        Route::get('/account', [AccountController::class, 'index'])->name('account.index');
        Route::get('/account/add', [AccountController::class, 'showAddAccountForm'])->name('account.add.form');
        Route::post('/account/add', [AccountController::class, 'addAccount'])->name('account.add');
        Route::post('/account/add/third-party', [AccountController::class, 'addThirdPartyAccount'])->name('account.add.third-party');
//    Crypto Wallet Route
        Route::post('/crypto-wallet', [CryptoWalletController::class, 'store'])->name('crypto-wallet.store');



        // Payment Gateway Management
        Route::prefix('payment-gateway')->name('payment-gateway.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'index'])->name('index');
            Route::get('/settlement', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'settlement'])->name('settlement');
            Route::get('/transaction/{transactionId}', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'showTransaction'])->name('transaction.show');

            // API Keys Management
            Route::post('/api-keys', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'storeApiKey'])->name('api-keys.store');
            Route::post('/api-keys/{apiClient}/regenerate', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'regenerateApiKey'])->name('api-keys.regenerate');
            Route::post('/api-keys/{apiClient}/toggle', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'toggleApiClientStatus'])->name('api-keys.toggle');
            Route::delete('/api-keys/{apiClient}', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'deleteApiClient'])->name('api-keys.delete');

            // Settlement Management
            Route::post('/settlement', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'processSettlement'])->name('settlement.process');
            Route::get('/wallet-info', [\App\Http\Controllers\Client\PaymentGatewayController::class, 'getWalletInfo'])->name('wallet.info');
        });

    });


    Route::prefix("verification")->group(function () {
        // Verification Routes
        Route::get('/', [VerificationController::class, 'showVerifyForm'])->name('client-registration.verify');
        Route::get('/document-pending', [VerificationController::class, 'showDocumentPending'])->name('client-registration.document-pending');
        Route::post('/send-email-otp', [VerificationController::class, 'sendEmailOtp']);
        Route::post('/verify-email-otp', [VerificationController::class, 'verifyEmailOtp']);
        Route::post('/send-phone-otp', [VerificationController::class, 'sendPhoneOtp']);
        Route::post('/verify-phone-otp', [VerificationController::class, 'verifyPhoneOtp']);
        Route::post('/verify-document', [VerificationController::class, 'verifyDocument']);

    });

    Route::get('/complete', function () {
        return redirect()->route('frontend.home')->with('success', 'Verification completed successfully');
    })->name('verification.complete')->middleware('auth');

    // Document upload route
    Route::post('/document/upload-document', [DocumentController::class, 'uploadDocument'])->name('document.upload-document');

    // Activity Routes
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/export/pdf', [ActivityController::class, 'exportPdf'])->name('activities.export.pdf');
    Route::get('/activities/export/excel', [ActivityController::class, 'exportExcel'])->name('activities.export.excel');
    Route::get('/activities/export/csv', [ActivityController::class, 'exportCsv'])->name('activities.export.csv');

    // Notification Routes
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');


});
