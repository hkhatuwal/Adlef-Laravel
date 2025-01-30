<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ClientLoginController;
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

    /*Client Auth Routes*/

    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\ClientLoginController::class, 'showLoginForm'])->name('client-login');
        Route::post('/login', [ClientLoginController::class, 'login'])->name('auth.login');

        Route::get('/register', [\App\Http\Controllers\ClientRegistrationController::class, 'showRegistrationForm'])->name('client-registration');
        Route::post('/register/save', [\App\Http\Controllers\ClientRegistrationController::class, 'saveRegistrationDetails'])->name('client-registration.save');
    });
    Route::middleware([\App\Http\Middleware\ClientAuthMiddleware::class, \Illuminate\Auth\Middleware\Authenticate::class])->group(function ($app) {
        Route::get('/logout', [\App\Http\Controllers\ClientLogoutController::class, 'logout'])->name('client-logout');


        // Verification Routes
        Route::get('/verification', [\App\Http\Controllers\VerificationController::class, 'showVerifyForm'])->name('client-registration.verify');
        Route::post('/verification/send-email-otp', [App\Http\Controllers\VerificationController::class, 'sendEmailOtp']);
        Route::post('/verification/verify-email-otp', [App\Http\Controllers\VerificationController::class, 'verifyEmailOtp']);
        Route::post('/verification/send-phone-otp', [App\Http\Controllers\VerificationController::class, 'sendPhoneOtp']);
        Route::post('/verification/verify-phone-otp', [App\Http\Controllers\VerificationController::class, 'verifyPhoneOtp']);
        Route::post('/verification/verify-document', [App\Http\Controllers\VerificationController::class, 'verifyDocument']);
        Route::get('/verification/complete', function () {
            return redirect()->route('frontend.home')->with('success', 'Verification completed successfully');
        })->name('verification.complete')->middleware('auth');
        // Document upload route
        Route::post('/document/upload-document', [App\Http\Controllers\DocumentController::class, 'uploadDocument'])->name('document.upload-document');

        // Account Management Routes
        Route::get('/account/add', [App\Http\Controllers\Frontend\AccountController::class, 'showAddAccountForm'])->name('account.add.form');
        Route::post('/account/add', [App\Http\Controllers\Frontend\AccountController::class, 'addAccount'])->name('account.add');
        Route::post('/account/add/third-party', [App\Http\Controllers\Frontend\AccountController::class, 'addThirdPartyAccount'])->name('account.add.third-party');
    });


});

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });


    Route::middleware('auth:web')->group(function () {
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });

});
