<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'frontend.'],function (){

    Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class,'index'])->name('home');
    Route::get('/about-us', [\App\Http\Controllers\Frontend\AboutController::class,'index'])->name('about');
    Route::get('/solutions', [\App\Http\Controllers\Frontend\SolutionsController::class,'index'])->name('solutions');

});
