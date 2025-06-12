<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OtcController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\DepositAccountController;
use App\Http\Controllers\Admin\UserActivityController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // Staff Management Routes
        Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');

        // Currency Management Routes
        Route::resource('currencies', CurrencyController::class);

        // Deposit Account Routes
        Route::resource('deposit-accounts', DepositAccountController::class);

        // User Management Routes
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/accounts', [UserController::class, 'accounts'])->name('users.accounts');
        Route::get('users/{user}/assets', [UserController::class, 'assets'])->name('users.assets');
        Route::get('users/{user}/commissions', [UserController::class, 'commissions'])->name('users.commissions');
        Route::put('users/{user}/commissions', [UserController::class, 'updateCommissions'])->name('users.update-commissions');
        Route::get('users/{user}/bank-accounts/{bankAccount}', [UserController::class, 'showBankAccount'])->name('users.bank-accounts.show');
        Route::post('users/{bankAccount}/verify-bank-account', [UserController::class, 'verifyBankAccount'])->name('users.verify-bank-account');
        Route::delete('users/{bankAccount}/verify-bank-account', [UserController::class, 'unverifyBankAccount'])->name('users.verify-bank-account');
        Route::post('users/{userProfile}/verify-document', [UserController::class, 'verifyDocument'])->name('users.verify-document');
        Route::delete('users/{userProfile}/verify-document', [UserController::class, 'unverifyDocument'])->name('users.verify-document');
        Route::get('/users/{user}/crypto-wallets/{cryptoWallet}', [UserController::class, 'showCryptoWallet'])->name('users.crypto-wallets.show');
        Route::post('/crypto-wallets/{cryptoWallet}/verify', [UserController::class, 'verifyCryptoWallet'])->name('users.verify-crypto-wallet');
        Route::delete('/crypto-wallets/{cryptoWallet}/verify', [UserController::class, 'unverifyCryptoWallet'])->name('users.verify-crypto-wallet');
        // Deposit Account Management Routes
        Route::get('users/{user}/deposit-accounts', [UserController::class, 'depositAccounts'])->name('users.deposit-accounts');
        Route::post('users/{user}/deposit-accounts', [UserController::class, 'assignDepositAccount'])->name('users.deposit-accounts.assign');
        Route::delete('users/{user}/deposit-accounts/{account}', [UserController::class, 'removeDepositAccount'])->name('users.deposit-accounts.remove');

        // Asset Transfer Routes
        Route::get('transfers', [\App\Http\Controllers\Admin\AssetTransferController::class, 'index'])->name('transfers.index');
        Route::get('transfers/{transfer}', [\App\Http\Controllers\Admin\AssetTransferController::class, 'show'])->name('transfers.show');
        Route::post('transfers/{transfer}/verify', [\App\Http\Controllers\Admin\AssetTransferController::class, 'verify'])->name('transfers.verify');
        Route::post('transfers/{transfer}/reject', [\App\Http\Controllers\Admin\AssetTransferController::class, 'reject'])->name('transfers.reject');
        Route::post('transfers/{transfer}/hold', [\App\Http\Controllers\Admin\AssetTransferController::class, 'hold'])->name('transfers.hold');
        
        // Payment Management Routes for Out Transfers
        Route::post('transfers/{transfer}/create-payment', [\App\Http\Controllers\Admin\AssetTransferController::class, 'createPayment'])->name('transfers.create-payment');
        Route::post('transfers/{transfer}/mark-payment-sent', [\App\Http\Controllers\Admin\AssetTransferController::class, 'markPaymentSent'])->name('transfers.mark-payment-sent');
        Route::post('transfers/{transfer}/confirm-payment', [\App\Http\Controllers\Admin\AssetTransferController::class, 'confirmPayment'])->name('transfers.confirm-payment');
        Route::post('transfers/{transfer}/retry-payment', [\App\Http\Controllers\Admin\AssetTransferController::class, 'retryPayment'])->name('transfers.retry-payment');
        
        // OTP-Protected Payment Routes
        Route::post('transfers/{transfer}/request-create-otp', [\App\Http\Controllers\Admin\AssetTransferController::class, 'requestCreateOtp'])->name('transfers.request-create-otp');
        Route::post('transfers/{transfer}/create-payment-with-otp', [\App\Http\Controllers\Admin\AssetTransferController::class, 'createPaymentWithOtp'])->name('transfers.create-payment-with-otp');
        Route::post('transfers/{transfer}/request-send-otp', [\App\Http\Controllers\Admin\AssetTransferController::class, 'requestSendOtp'])->name('transfers.request-send-otp');
        Route::post('transfers/{transfer}/send-payment-with-otp', [\App\Http\Controllers\Admin\AssetTransferController::class, 'sendPaymentWithOtp'])->name('transfers.send-payment-with-otp');

        // OTC Routes
        Route::get('otc', [OtcController::class, 'index'])->name('otc.index');
        Route::get('otc/{otc}', [OtcController::class, 'show'])->name('otc.show');
        Route::post('otc/{otc}/process', [OtcController::class, 'process'])->name('otc.process');
        Route::post('otc/{otc}/complete', [OtcController::class, 'complete'])->name('otc.complete');
        Route::post('otc/{otc}/hold', [OtcController::class, 'hold'])->name('otc.hold');
        Route::post('otc/{otc}/reject', [OtcController::class, 'reject'])->name('otc.reject');

        // User Activities Routes
        Route::get('activities', [UserActivityController::class, 'index'])->name('activities.index');
        Route::get('activities/{activity}', [UserActivityController::class, 'show'])->name('activities.show');

        // Settings Routes
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');
        Route::post('settings/create', [\App\Http\Controllers\Admin\SettingController::class, 'createSetting'])->name('settings.create');

        // Help Center Routes
        Route::get('help-center', [\App\Http\Controllers\Admin\HelpCenterController::class, 'index'])->name('help-center.index');
        Route::get('help-center/{helpRequest}', [\App\Http\Controllers\Admin\HelpCenterController::class, 'show'])->name('help-center.show');
        Route::put('help-center/{helpRequest}', [\App\Http\Controllers\Admin\HelpCenterController::class, 'update'])->name('help-center.update');

        // Help Center Categories Routes
        Route::get('help-center-categories', [\App\Http\Controllers\Admin\HelpCenterController::class, 'categories'])->name('help-center.categories');
        Route::post('help-center-categories', [\App\Http\Controllers\Admin\HelpCenterController::class, 'storeCategory'])->name('help-center.categories.store');
        Route::put('help-center-categories/{category}', [\App\Http\Controllers\Admin\HelpCenterController::class, 'updateCategory'])->name('help-center.categories.update');
        Route::delete('help-center-categories/{category}', [\App\Http\Controllers\Admin\HelpCenterController::class, 'destroyCategory'])->name('help-center.categories.destroy');
    });
});
