<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('generate', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

Route::group(['as' => 'frontend.'],function (){

    Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class,'index'])->name('home');
    Route::get('/about-us', [\App\Http\Controllers\Frontend\AboutController::class,'index'])->name('about');
    Route::get('/solutions', [\App\Http\Controllers\Frontend\SolutionsController::class,'index'])->name('solutions');
    Route::get('/news-insights', [\App\Http\Controllers\Frontend\NewsInsightsController::class,'index'])->name('news-insights');
    Route::get('/careers', [\App\Http\Controllers\Frontend\CareerController::class,'index'])->name('careers');

});




Route::group(['as' => 'admin.','prefix' => 'admin'],function (){
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });


    Route::middleware('auth:web')->group(function () {
        Route::resource('posts',\App\Http\Controllers\Admin\PostController::class);
        Route::post('logout',[AuthController::class, 'logout'])->name('logout');
    });

});
