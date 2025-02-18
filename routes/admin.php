<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OtcController;
use App\Http\Controllers\Admin\StaffController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
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

        // User Management Routes
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/accounts', [UserController::class, 'accounts'])->name('users.accounts');
        Route::get('users/{user}/assets', [UserController::class, 'assets'])->name('users.assets');
        Route::get('users/{user}/bank-accounts/{bankAccount}', [UserController::class, 'showBankAccount'])->name('users.bank-accounts.show');
        Route::post('users/{bankAccount}/verify-bank-account', [UserController::class, 'verifyBankAccount'])->name('users.verify-bank-account');
        Route::delete('users/{bankAccount}/verify-bank-account', [UserController::class, 'unverifyBankAccount'])->name('users.verify-bank-account');
        Route::post('users/{userProfile}/verify-document', [UserController::class, 'verifyDocument'])->name('users.verify-document');
        Route::delete('users/{userProfile}/verify-document', [UserController::class, 'unverifyDocument'])->name('users.verify-document');

        // Asset Transfer Routes
        Route::get('transfers', [\App\Http\Controllers\Admin\AssetTransferController::class, 'index'])->name('transfers.index');
        Route::get('transfers/{transfer}', [\App\Http\Controllers\Admin\AssetTransferController::class, 'show'])->name('transfers.show');
        Route::post('transfers/{transfer}/verify', [\App\Http\Controllers\Admin\AssetTransferController::class, 'verify'])->name('transfers.verify');
        Route::post('transfers/{transfer}/reject', [\App\Http\Controllers\Admin\AssetTransferController::class, 'reject'])->name('transfers.reject');

        // OTC Routes
        Route::get('otc', [OtcController::class, 'index'])->name('otc.index');
        Route::get('otc/{otc}', [OtcController::class, 'show'])->name('otc.show');
        Route::post('otc/{otc}/process', [OtcController::class, 'process'])->name('otc.process');
        Route::post('otc/{otc}/complete', [OtcController::class, 'complete'])->name('otc.complete');
        Route::post('otc/{otc}/reject', [OtcController::class, 'reject'])->name('otc.reject');

        // Settings Routes
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');
        Route::post('settings/create', [\App\Http\Controllers\Admin\SettingController::class, 'createSetting'])->name('settings.create');
    });
});
