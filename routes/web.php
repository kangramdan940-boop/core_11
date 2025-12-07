<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterCustomerController;
use App\Http\Controllers\Admin\MitraBrankasController;
use App\Http\Controllers\Admin\MasterAgenController;
use App\Http\Controllers\Admin\MasterAdminController;
use App\Http\Controllers\Admin\MasterGoldPriceController;
use App\Http\Controllers\Admin\MasterGoldReadyStockController;
use App\Http\Controllers\Admin\MasterBrandEmasController;
use App\Http\Controllers\Admin\MasterHomeSliderController;
use App\Http\Controllers\Admin\MasterMitraKomisiController;
use App\Http\Controllers\Admin\MasterSettingController;
use App\Http\Controllers\Admin\MasterMenuHomeCustomerController;
use App\Http\Controllers\Admin\MasterProdukDanLayananController;
use App\Http\Controllers\Admin\MasterGramasiEmasController;
use App\Http\Controllers\Admin\TransPaymentLogController;
use App\Http\Controllers\Admin\TransPoController;
use App\Http\Controllers\Admin\TransCicilanController;
use App\Http\Controllers\Admin\TransReadyController;
use App\Http\Controllers\Admin\TransCicilanPaymentController;
use App\Http\Controllers\Front\CustomerAuthController;
use App\Http\Controllers\Front\MitraAuthController;
use App\Http\Controllers\Front\CustomerPoController;
use App\Http\Controllers\Front\CustomerReadyController;
use App\Http\Controllers\Front\CustomerCicilanController;
use App\Http\Controllers\Front\FrontController;

Route::get('/', [FrontController::class, 'home']);

Route::get('/customer/login', [CustomerAuthController::class, 'showLoginForm'])
    ->name('customer.login');

Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/customer/login', [CustomerAuthController::class, 'login'])
    ->name('customer.login.submit');

Route::get('/customer/register', [CustomerAuthController::class, 'showRegisterForm'])
    ->name('customer.register');

Route::post('/customer/register', [CustomerAuthController::class, 'register'])
    ->name('customer.register.submit');

Route::get('/customer/forgot-password', [FrontController::class, 'customerForgotPassword'])
    ->name('customer.forgot-password');

Route::middleware('auth')->prefix('customer')->group(function () {
    Route::get('/dashboard', [FrontController::class, 'customerDashboard'])->name('customer.dashboard');
    Route::get('/product-dan-layanan', [FrontController::class, 'customerProductDanLayanan'])->name('customer.product-dan-layanan');
    Route::get('/profile', [FrontController::class, 'customerProfile'])->name('customer.profile');
    Route::put('/profile', [FrontController::class, 'updateCustomerProfile'])->name('customer.profile.update');
    Route::get('/all-order', [FrontController::class, 'customerAllOrders'])->name('customer.all-order');

    Route::get('/po/create', [FrontController::class, 'customerPoCreate'])
        ->name('customer.po.create');

    Route::get('/pre-order-emas', [FrontController::class, 'customerPoCreate'])
        ->name('customer.pre-order-emas');


    Route::post('/po', [CustomerPoController::class, 'store'])
        ->name('customer.po.store');

    Route::get('/po/{po}', [CustomerPoController::class, 'show'])
        ->name('customer.po.show');

    Route::post('/po/{po}/confirm-payment', [CustomerPoController::class, 'confirmPayment'])
        ->name('customer.po.confirm-payment');

    Route::get('/ready', [CustomerReadyController::class, 'index'])
        ->name('customer.ready.index');
    Route::get('/ready/{stock}', [CustomerReadyController::class, 'stock'])
        ->name('customer.ready.stock');
    Route::get('/ready/{stock}/buy', [CustomerReadyController::class, 'buy'])
        ->name('customer.ready.buy');
    Route::post('/ready', [CustomerReadyController::class, 'store'])
        ->name('customer.ready.store');
    Route::get('/ready-trans/{ready}', [CustomerReadyController::class, 'show'])
        ->name('customer.ready.show');
    Route::post('/ready-trans/{ready}/confirm-payment', [CustomerReadyController::class, 'confirmPayment'])
        ->name('customer.ready.confirm-payment');

    Route::get('/cicilan', [CustomerCicilanController::class, 'index'])
        ->name('customer.cicilan.index');
    Route::get('/cicilan/{stock}', [CustomerCicilanController::class, 'stock'])
        ->name('customer.cicilan.stock');
    Route::post('/cicilan', [CustomerCicilanController::class, 'store'])
        ->name('customer.cicilan.store');
    Route::get('/cicilan-kontrak/{contract}', [CustomerCicilanController::class, 'show'])
        ->name('customer.cicilan.show');
    Route::post('/cicilan-payment/{payment}/confirm-payment', [CustomerCicilanController::class, 'confirmPayment'])
        ->name('customer.cicilan.confirm-payment');

    Route::post('/logout', [CustomerAuthController::class, 'logout'])
        ->name('customer.logout');
});

Route::get('/mitra/login', [MitraAuthController::class, 'showLoginForm'])
    ->name('mitra.login');
Route::post('/mitra/login', [MitraAuthController::class, 'login'])
    ->name('mitra.login.submit');

Route::get('/mitra/register', [MitraAuthController::class, 'showRegisterForm'])
    ->name('mitra.register');
Route::post('/mitra/register', [MitraAuthController::class, 'register'])
    ->name('mitra.register.submit');

Route::middleware('auth')->get('/mitra/dashboard', [FrontController::class, 'mitraDashboard'])
    ->name('mitra.dashboard');

Route::middleware('auth')->get('/mitra/komisi', [FrontController::class, 'mitraKomisiIndex'])
    ->name('mitra.komisi.index');

Route::middleware('auth')->get('/mitra/profile', [FrontController::class, 'mitraProfile'])
    ->name('mitra.profile');
Route::middleware('auth')->put('/mitra/profile', [FrontController::class, 'updateMitraProfile'])
    ->name('mitra.profile.update');

Route::middleware('auth')->post('/mitra/logout', [MitraAuthController::class, 'logout'])
    ->name('mitra.logout');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('admin.login');

    Route::post('/admin/login', [AdminAuthController::class, 'login'])
        ->name('admin.login.submit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::prefix('trans/po')->name('admin.trans.po.')->group(function () {
        Route::post('{po}/mitra-komisi', [\App\Http\Controllers\Admin\TransPoMitraKomisiController::class, 'store'])->name('mitra-komisi.store');
    });
    Route::prefix('trans/po')->name('admin.trans.po.')->group(function () {
        Route::delete('mitra-komisi/{assignment}', [\App\Http\Controllers\Admin\TransPoMitraKomisiController::class, 'destroy'])->name('mitra-komisi.destroy');
    });
    // LIST
    Route::get('/master/customers', [MasterCustomerController::class, 'index'])
        ->name('admin.master.customers.index');

    // CREATE FORM
    Route::get('/master/customers/create', [MasterCustomerController::class, 'create'])
        ->name('admin.master.customers.create');

    // SIMPAN DATA BARU
    Route::post('/master/customers', [MasterCustomerController::class, 'store'])
        ->name('admin.master.customers.store');

    // EDIT FORM
    Route::get('/master/customers/{customer}/edit', [MasterCustomerController::class, 'edit'])
        ->name('admin.master.customers.edit');

    // UPDATE DATA
    Route::put('/master/customers/{customer}', [MasterCustomerController::class, 'update'])
        ->name('admin.master.customers.update');

    // HAPUS DATA
    Route::delete('/master/customers/{customer}', [MasterCustomerController::class, 'destroy'])
        ->name('admin.master.customers.destroy');

    Route::get('/master/mitra-brankas', [MitraBrankasController::class, 'index'])
        ->name('admin.master.mitra-brankas.index');

    Route::get('/master/mitra-brankas/create', [MitraBrankasController::class, 'create'])
        ->name('admin.master.mitra-brankas.create');

    Route::post('/master/mitra-brankas', [MitraBrankasController::class, 'store'])
        ->name('admin.master.mitra-brankas.store');

    Route::get('/master/mitra-brankas/{mitra}/edit', [MitraBrankasController::class, 'edit'])
        ->name('admin.master.mitra-brankas.edit');

    Route::put('/master/mitra-brankas/{mitra}', [MitraBrankasController::class, 'update'])
        ->name('admin.master.mitra-brankas.update');

    Route::delete('/master/mitra-brankas/{mitra}', [MitraBrankasController::class, 'destroy'])
        ->name('admin.master.mitra-brankas.destroy');

    Route::get('/master/agens', [MasterAgenController::class, 'index'])
        ->name('admin.master.agens.index');

    Route::get('/master/agens/create', [MasterAgenController::class, 'create'])
        ->name('admin.master.agens.create');

    Route::post('/master/agens', [MasterAgenController::class, 'store'])
        ->name('admin.master.agens.store');

    Route::get('/master/agens/{agen}/edit', [MasterAgenController::class, 'edit'])
        ->name('admin.master.agens.edit');

    Route::put('/master/agens/{agen}', [MasterAgenController::class, 'update'])
        ->name('admin.master.agens.update');

    Route::delete('/master/agens/{agen}', [MasterAgenController::class, 'destroy'])
        ->name('admin.master.agens.destroy');

    Route::get('/master/admins', [MasterAdminController::class, 'index'])
        ->name('admin.master.admins.index');

    Route::get('/master/admins/create', [MasterAdminController::class, 'create'])
        ->name('admin.master.admins.create');

    Route::post('/master/admins', [MasterAdminController::class, 'store'])
        ->name('admin.master.admins.store');

    Route::get('/master/admins/{admin}/edit', [MasterAdminController::class, 'edit'])
        ->name('admin.master.admins.edit');

    Route::put('/master/admins/{admin}', [MasterAdminController::class, 'update'])
        ->name('admin.master.admins.update');

    Route::delete('/master/admins/{admin}', [MasterAdminController::class, 'destroy'])
        ->name('admin.master.admins.destroy');

    Route::get('/master/brand-emas', [MasterBrandEmasController::class, 'index'])
        ->name('admin.master.brand-emas.index');
    Route::get('/master/brand-emas/create', [MasterBrandEmasController::class, 'create'])
        ->name('admin.master.brand-emas.create');
    Route::post('/master/brand-emas', [MasterBrandEmasController::class, 'store'])
        ->name('admin.master.brand-emas.store');
    Route::get('/master/brand-emas/{brand}/edit', [MasterBrandEmasController::class, 'edit'])
        ->name('admin.master.brand-emas.edit');
    Route::put('/master/brand-emas/{brand}', [MasterBrandEmasController::class, 'update'])
        ->name('admin.master.brand-emas.update');
    Route::delete('/master/brand-emas/{brand}', [MasterBrandEmasController::class, 'destroy'])
        ->name('admin.master.brand-emas.destroy');

    Route::get('/master/home-sliders', [MasterHomeSliderController::class, 'index'])
        ->name('admin.master.home-slider.index');
    Route::get('/master/home-sliders/create', [MasterHomeSliderController::class, 'create'])
        ->name('admin.master.home-slider.create');
    Route::post('/master/home-sliders', [MasterHomeSliderController::class, 'store'])
        ->name('admin.master.home-slider.store');
    Route::get('/master/home-sliders/{slider}/edit', [MasterHomeSliderController::class, 'edit'])
        ->name('admin.master.home-slider.edit');
    Route::put('/master/home-sliders/{slider}', [MasterHomeSliderController::class, 'update'])
        ->name('admin.master.home-slider.update');
    Route::delete('/master/home-sliders/{slider}', [MasterHomeSliderController::class, 'destroy'])
        ->name('admin.master.home-slider.destroy');

    Route::get('/master/menu-home-customer', [MasterMenuHomeCustomerController::class, 'index'])
        ->name('admin.master.menu-home-customer.index');
    Route::get('/master/menu-home-customer/create', [MasterMenuHomeCustomerController::class, 'create'])
        ->name('admin.master.menu-home-customer.create');
    Route::post('/master/menu-home-customer', [MasterMenuHomeCustomerController::class, 'store'])
        ->name('admin.master.menu-home-customer.store');
    Route::get('/master/menu-home-customer/{menu}/edit', [MasterMenuHomeCustomerController::class, 'edit'])
        ->name('admin.master.menu-home-customer.edit');
    Route::put('/master/menu-home-customer/{menu}', [MasterMenuHomeCustomerController::class, 'update'])
        ->name('admin.master.menu-home-customer.update');
    Route::delete('/master/menu-home-customer/{menu}', [MasterMenuHomeCustomerController::class, 'destroy'])
        ->name('admin.master.menu-home-customer.destroy');

    Route::get('/master/produk-layanan', [MasterProdukDanLayananController::class, 'index'])
        ->name('admin.master.produk-layanan.index');
    Route::get('/master/produk-layanan/create', [MasterProdukDanLayananController::class, 'create'])
        ->name('admin.master.produk-layanan.create');
    Route::post('/master/produk-layanan', [MasterProdukDanLayananController::class, 'store'])
        ->name('admin.master.produk-layanan.store');
    Route::get('/master/produk-layanan/{item}/edit', [MasterProdukDanLayananController::class, 'edit'])
        ->name('admin.master.produk-layanan.edit');
    Route::put('/master/produk-layanan/{item}', [MasterProdukDanLayananController::class, 'update'])
        ->name('admin.master.produk-layanan.update');
    Route::delete('/master/produk-layanan/{item}', [MasterProdukDanLayananController::class, 'destroy'])
        ->name('admin.master.produk-layanan.destroy');

    Route::get('/master/gramasi-emas', [MasterGramasiEmasController::class, 'index'])
        ->name('admin.master.gramasi-emas.index');
    Route::get('/master/gramasi-emas/create', [MasterGramasiEmasController::class, 'create'])
        ->name('admin.master.gramasi-emas.create');
    Route::post('/master/gramasi-emas', [MasterGramasiEmasController::class, 'store'])
        ->name('admin.master.gramasi-emas.store');
    Route::get('/master/gramasi-emas/{item}/edit', [MasterGramasiEmasController::class, 'edit'])
        ->name('admin.master.gramasi-emas.edit');
    Route::put('/master/gramasi-emas/{item}', [MasterGramasiEmasController::class, 'update'])
        ->name('admin.master.gramasi-emas.update');
    Route::delete('/master/gramasi-emas/{item}', [MasterGramasiEmasController::class, 'destroy'])
        ->name('admin.master.gramasi-emas.destroy');

    Route::get('/master/gold-prices', [MasterGoldPriceController::class, 'index'])
        ->name('admin.master.gold-prices.index');

    Route::get('/master/gold-prices/create', [MasterGoldPriceController::class, 'create'])
        ->name('admin.master.gold-prices.create');

    Route::post('/master/gold-prices', [MasterGoldPriceController::class, 'store'])
        ->name('admin.master.gold-prices.store');

    Route::get('/master/gold-prices/{price}/edit', [MasterGoldPriceController::class, 'edit'])
        ->name('admin.master.gold-prices.edit');

    Route::put('/master/gold-prices/{price}', [MasterGoldPriceController::class, 'update'])
        ->name('admin.master.gold-prices.update');

    Route::delete('/master/gold-prices/{price}', [MasterGoldPriceController::class, 'destroy'])
        ->name('admin.master.gold-prices.destroy');

    Route::get('/master/ready-stocks', [MasterGoldReadyStockController::class, 'index'])
        ->name('admin.master.ready-stocks.index');

    Route::get('/master/ready-stocks/create', [MasterGoldReadyStockController::class, 'create'])
        ->name('admin.master.ready-stocks.create');

    Route::post('/master/ready-stocks', [MasterGoldReadyStockController::class, 'store'])
        ->name('admin.master.ready-stocks.store');

    Route::get('/master/ready-stocks/{stock}/edit', [MasterGoldReadyStockController::class, 'edit'])
        ->name('admin.master.ready-stocks.edit');

    Route::put('/master/ready-stocks/{stock}', [MasterGoldReadyStockController::class, 'update'])
        ->name('admin.master.ready-stocks.update');

    Route::delete('/master/ready-stocks/{stock}', [MasterGoldReadyStockController::class, 'destroy'])
        ->name('admin.master.ready-stocks.destroy');

    Route::get('/master/mitra-komisi', [MasterMitraKomisiController::class, 'index'])
        ->name('admin.master.mitra-komisi.index');

    Route::get('/master/mitra-komisi/create', [MasterMitraKomisiController::class, 'create'])
        ->name('admin.master.mitra-komisi.create');

    Route::post('/master/mitra-komisi', [MasterMitraKomisiController::class, 'store'])
        ->name('admin.master.mitra-komisi.store');

    Route::get('/master/mitra-komisi/{komisi}/edit', [MasterMitraKomisiController::class, 'edit'])
        ->name('admin.master.mitra-komisi.edit');

    Route::put('/master/mitra-komisi/{komisi}', [MasterMitraKomisiController::class, 'update'])
        ->name('admin.master.mitra-komisi.update');

    Route::delete('/master/mitra-komisi/{komisi}', [MasterMitraKomisiController::class, 'destroy'])
        ->name('admin.master.mitra-komisi.destroy');

    Route::get('/master/settings', [MasterSettingController::class, 'index'])
        ->name('admin.master.settings.index');

    Route::get('/master/settings/create', [MasterSettingController::class, 'create'])
        ->name('admin.master.settings.create');

    Route::post('/master/settings', [MasterSettingController::class, 'store'])
        ->name('admin.master.settings.store');

    Route::get('/master/settings/{setting}/edit', [MasterSettingController::class, 'edit'])
        ->name('admin.master.settings.edit');

    Route::put('/master/settings/{setting}', [MasterSettingController::class, 'update'])
        ->name('admin.master.settings.update');

    Route::delete('/master/settings/{setting}', [MasterSettingController::class, 'destroy'])
        ->name('admin.master.settings.destroy');

    Route::get('/trans/payment-logs', [TransPaymentLogController::class, 'index'])
        ->name('admin.trans.payment-logs.index');

    Route::get('/trans/payment-logs/{log}', [TransPaymentLogController::class, 'show'])
        ->name('admin.trans.payment-logs.show');

    Route::post('/trans/payment-logs/{log}/approve', [TransPaymentLogController::class, 'approve'])
        ->name('admin.trans.payment-logs.approve');

    Route::post('/trans/payment-logs/{log}/reject', [TransPaymentLogController::class, 'reject'])
        ->name('admin.trans.payment-logs.reject');

    Route::get('/trans/po', [TransPoController::class, 'index'])
        ->name('admin.trans.po.index');

    Route::get('/trans/po/{po}', [TransPoController::class, 'show'])
        ->name('admin.trans.po.show');

    Route::post('/trans/po/{po}/approve-payment', [TransPoController::class, 'approvePayment'])
        ->name('admin.trans.po.approve-payment');

    Route::post('/trans/po/{po}/reject-payment', [TransPoController::class, 'rejectPayment'])
        ->name('admin.trans.po.reject-payment');

    Route::post('/trans/po/{po}/status', [TransPoController::class, 'updateStatus'])
        ->name('admin.trans.po.update-status');

    Route::get('/trans/cicilan', [TransCicilanController::class, 'index'])
        ->name('admin.trans.cicilan.index');

    Route::get('/trans/cicilan/{contract}', [TransCicilanController::class, 'show'])
        ->name('admin.trans.cicilan.show');

    Route::get('/trans/ready', [TransReadyController::class, 'index'])
        ->name('admin.trans.ready.index');

    Route::get('/trans/ready/{ready}', [TransReadyController::class, 'show'])
        ->name('admin.trans.ready.show');

    Route::post('/trans/ready/{ready}/status', [TransReadyController::class, 'updateStatus'])
        ->name('admin.trans.ready.update-status');

    Route::get('/trans/cicilan-payments', [TransCicilanPaymentController::class, 'index'])
        ->name('admin.trans.cicilan-payments.index');

    Route::get('/trans/cicilan-payments/{payment}', [TransCicilanPaymentController::class, 'show'])
        ->name('admin.trans.cicilan-payments.show');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');
});