<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    Route::get('/public/app-configurations', [\App\Http\Controllers\Api\MobileInformationController::class, 'show'])->name('public.mobile-informations');
Route::get('/public/payment-settings', [\App\Http\Controllers\Api\PaymentSettingApiController::class, 'show'])->name('public.payment-settings');
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
    Route::post('/customers/po/checkout', [\App\Http\Controllers\Api\CustomerCheckoutApiController::class, 'checkout'])->name('customer.po.checkout');
    Route::post('/customers/ready-stocks/checkout', [\App\Http\Controllers\Api\ReadyCheckoutApiController::class, 'checkout'])->name('customer.ready-stocks.checkout');
    Route::get('/customers/keranjang', [\App\Http\Controllers\Api\CustomerCheckoutApiController::class, 'carts'])->name('customer.keranjang.index');
    Route::get('/customers/keranjang/{id}', [\App\Http\Controllers\Api\CustomerCheckoutApiController::class, 'cart'])->whereNumber('id')->name('customer.keranjang.show');
    Route::post('/customers/keranjang/{id}/confirm-payment', [\App\Http\Controllers\Api\CustomerCheckoutApiController::class, 'confirmPayment'])->whereNumber('id')->name('customer.keranjang.confirm-payment');
    Route::post('/customers/keranjang/{id}/complete-and-review', [\App\Http\Controllers\Api\CustomerCheckoutApiController::class, 'completeAndReview'])->whereNumber('id')->name('customer.keranjang.complete-and-review');

    // Cicilan (Bearer)
    Route::get('/customers/cicilan/records', [\App\Http\Controllers\Api\CicilanApiController::class, 'publicRecords'])->name('customer.cicilan.records');
    Route::get('/customers/cicilan/records/{id}', [\App\Http\Controllers\Api\CicilanApiController::class, 'publicRecord'])->whereNumber('id')->name('customer.cicilan.records.show');
    Route::get('/customers/cicilan/contracts', [\App\Http\Controllers\Api\CicilanApiController::class, 'contracts'])->name('customer.cicilan.contracts');
    Route::get('/customers/cicilan/contracts/{contract}', [\App\Http\Controllers\Api\CicilanApiController::class, 'contract'])->name('customer.cicilan.contracts.show');
    Route::post('/customers/cicilan/records/{recordId}/create-contract', [\App\Http\Controllers\Api\CicilanApiController::class, 'createContractFromRecord'])->whereNumber('recordId')->name('customer.cicilan.records.create-contract');
    Route::post('/customers/cicilan/payments/{payment}/confirm-payment', [\App\Http\Controllers\Api\CicilanApiController::class, 'confirmPayment'])->whereNumber('payment')->name('customer.cicilan.payments.confirm-payment');

    Route::get('/customers/portfolio/summary', [\App\Http\Controllers\Api\PortfolioApiController::class, 'summary'])->name('customer.portfolio.summary');
    Route::get('/customers/notifications', [\App\Http\Controllers\Api\NotificationApiController::class, 'index'])->name('customer.notifications');
    Route::post('/customers/auth/logout', [\App\Http\Controllers\Api\CustomerAuthApiController::class, 'logout'])->name('customer.auth.logout');
});