<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Client\ClientLoginController;
use App\Http\Controllers\Client\CryptoWalletController;
use Illuminate\Support\Facades\Route;

Route::get('install', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
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
});

// Client Routes
Route::group(['prefix' => 'client', 'as' => 'client.', 'middleware' => ['auth:client']], function () {
    Route::post('/crypto-wallet', [\App\Http\Controllers\Client\CryptoWalletController::class, 'store'])->name('crypto-wallet.store');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);


    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });

});
