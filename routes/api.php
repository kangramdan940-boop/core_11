<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/api/v1')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    Route::get('/public/app-configurations', [\App\Http\Controllers\Api\MobileInformationController::class, 'show'])->name('public.mobile-informations');
    Route::post('/auth/register', [\App\Http\Controllers\Api\CustomerAuthApiController::class, 'register'])->name('public.customer.register');
    Route::post('/auth/login', [\App\Http\Controllers\Api\CustomerAuthApiController::class, 'login'])->name('public.customer.login');
});

Route::prefix('/api/v1')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->middleware('auth:sanctum')->group(function () {
    Route::get('/customers/products-and-services', [\App\Http\Controllers\Api\MasterProdukDanLayananApiController::class, 'index'])->name('customer.produk-dan-layanan');
    Route::get('/customers/products-ready-stocks', [\App\Http\Controllers\Api\MasterGoldReadyStockApiController::class, 'index'])->name('customer.ready-stocks');
});