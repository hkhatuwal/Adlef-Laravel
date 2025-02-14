<?php

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\OtcController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ActivityController;

/*Client Auth Routes*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Client\ClientLoginController::class, 'showLoginForm'])->name('client-login');
    Route::post('/login', [\App\Http\Controllers\Client\ClientLoginController::class, 'login'])->name('auth.login');

    Route::get('/register', [\App\Http\Controllers\Client\ClientRegistrationController::class, 'showRegistrationForm'])->name('client-registration');
    Route::post('/register/save', [\App\Http\Controllers\Client\ClientRegistrationController::class, 'saveRegistrationDetails'])->name('client-registration.save');
});
Route::middleware([\App\Http\Middleware\ClientMiddleware::class, \Illuminate\Auth\Middleware\Authenticate::class])->name('client.')->group(function ($app) {

    Route::middleware([\App\Http\Middleware\ClientVerifyMiddleware::class])->group(function ($app) {
        Route::get('/logout', [\App\Http\Controllers\Client\ClientLogoutController::class, 'logout'])->name('client-logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/holdings', [DashboardController::class, 'holdings'])->name('holdings');
        Route::get('/activity', [DashboardController::class, 'activity'])->name('activity');

        // Asset Transfer Routes
        Route::get('/transfer', [\App\Http\Controllers\Client\AssetTransferController::class, 'index'])->name('transfer');
        Route::get('/transfer/in', [\App\Http\Controllers\Client\AssetTransferController::class, 'transferIn'])->name('transfer.in');
        Route::get('/transfer/out', [\App\Http\Controllers\Client\AssetTransferController::class, 'transferOut'])->name('transfer.out');
        Route::get('/transfer/show/{transfer}', [\App\Http\Controllers\Client\AssetTransferController::class, 'show'])->name('transfer.show');
        Route::post('/transfer/transfer/in', [\App\Http\Controllers\Client\AssetTransferController::class, 'storeTransferIn'])->name('transfer.in.post');
        Route::post('/transfer/transfer/out', [\App\Http\Controllers\Client\AssetTransferController::class, 'storeTransferOut'])->name('transfer.out.post');
        Route::post('/transfer/calculate-fee', [\App\Http\Controllers\Client\AssetTransferController::class, 'calculateFee'])->name('client.transfer.calculate-fee');

        // OTC Exchange Routes
        Route::prefix('otc')->name('otc.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\OtcController::class, 'index'])->name('index');
            Route::post('/calculate', [\App\Http\Controllers\Client\OtcController::class, 'calculate'])->name('calculate');
            Route::post('/confirm', [\App\Http\Controllers\Client\OtcController::class, 'confirmExchange'])->name('confirm');
            Route::get('/success/{id}', [OtcController::class, 'showSuccess'])->name('success');
            Route::get('/show/{id}', [OtcController::class, 'show'])->name('show');
        });

        // Account Management Routes
        Route::get('/account', [\App\Http\Controllers\Client\AccountController::class, 'index'])->name('account.index');
        Route::get('/account/add', [\App\Http\Controllers\Client\AccountController::class, 'showAddAccountForm'])->name('account.add.form');
        Route::post('/account/add', [\App\Http\Controllers\Client\AccountController::class, 'addAccount'])->name('account.add');
        Route::post('/account/add/third-party', [\App\Http\Controllers\Client\AccountController::class, 'addThirdPartyAccount'])->name('account.add.third-party');
//    Crypto Wallet Route
        Route::post('/crypto-wallet', [\App\Http\Controllers\Client\CryptoWalletController::class, 'store'])->name('crypto-wallet.store');


    });


    Route::prefix("verification")->group(function () {
        // Verification Routes
        Route::get('/', [\App\Http\Controllers\Client\VerificationController::class, 'showVerifyForm'])->name('client-registration.verify');
        Route::post('/send-email-otp', [\App\Http\Controllers\Client\VerificationController::class, 'sendEmailOtp']);
        Route::post('/verify-email-otp', [\App\Http\Controllers\Client\VerificationController::class, 'verifyEmailOtp']);
        Route::post('/send-phone-otp', [\App\Http\Controllers\Client\VerificationController::class, 'sendPhoneOtp']);
        Route::post('/verify-phone-otp', [\App\Http\Controllers\Client\VerificationController::class, 'verifyPhoneOtp']);
        Route::post('/verify-document', [\App\Http\Controllers\Client\VerificationController::class, 'verifyDocument']);

    });

    Route::get('/complete', function () {
        return redirect()->route('frontend.home')->with('success', 'Verification completed successfully');
    })->name('verification.complete')->middleware('auth');

    // Document upload route
    Route::post('/document/upload-document', [\App\Http\Controllers\Client\DocumentController::class, 'uploadDocument'])->name('document.upload-document');

    // Activity Routes
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

});
