<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PromoCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('promo-codes', [PromoCodeController::class, 'store']);
    Route::get('promo-codes/validate', [PromoCodeController::class, 'checkValidity']);
});
