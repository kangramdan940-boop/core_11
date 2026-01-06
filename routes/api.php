<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    Route::get('/public/app-configurations', [\App\Http\Controllers\Api\MobileInformationController::class, 'show'])->name('public.mobile-informations');
    Route::post('/auth/register', [\App\Http\Controllers\Api\CustomerAuthApiController::class, 'register'])->name('public.customer.register');
    Route::post('/auth/login', [\App\Http\Controllers\Api\CustomerAuthApiController::class, 'login'])->name('public.customer.login');
});

Route::prefix('v1')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->middleware('auth:sanctum')->group(function () {
    Route::get('/customers/products-and-services', [\App\Http\Controllers\Api\MasterProdukDanLayananApiController::class, 'index'])->name('customer.produk-dan-layanan');
    Route::get('/customers/products-ready-stocks', [\App\Http\Controllers\Api\MasterGoldReadyStockApiController::class, 'index'])->name('customer.ready-stocks');
    Route::get('/customers/products-ready-stocks/detail/{id}', [\App\Http\Controllers\Api\MasterGoldReadyStockApiController::class, 'show'])->whereNumber('id')->name('customer.ready-stocks.detail');
    Route::get('/customers/addresses', [\App\Http\Controllers\Api\MasterCustomerAddressApiController::class, 'index'])->name('customer.addresses');
    Route::post('/customers/addresses', [\App\Http\Controllers\Api\MasterCustomerAddressApiController::class, 'store'])->name('customer.addresses.store');
    Route::delete('/customers/addresses/{id}', [\App\Http\Controllers\Api\MasterCustomerAddressApiController::class, 'destroy'])->whereNumber('id')->name('customer.addresses.destroy');
    Route::get('/customers/jne/cities', [\App\Http\Controllers\Api\JneProxyApiController::class, 'cities'])->name('customer.api.jne.cities');
    Route::get('/customers/jne/shipping-fee', [\App\Http\Controllers\Api\JneProxyApiController::class, 'shippingFee'])->name('customer.api.jne.shipping-fee');
    Route::delete('/customers/addresses/{id}', [\App\Http\Controllers\Api\MasterCustomerAddressApiController::class, 'destroy'])->whereNumber('id')->name('customer.addresses.destroy');
    Route::post('/customers/po/checkout', [\App\Http\Controllers\Api\CustomerCheckoutApiController::class, 'checkout'])->name('customer.po.checkout');
    Route::get('/customers/keranjang/{id}', [\App\Http\Controllers\Api\CustomerCheckoutApiController::class, 'cart'])->whereNumber('id')->name('customer.keranjang.show');
});