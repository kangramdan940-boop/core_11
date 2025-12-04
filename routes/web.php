<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterCustomerController;
use App\Http\Controllers\Admin\MitraBrankasController;
use App\Http\Controllers\Admin\MasterAgenController;
use App\Http\Controllers\Admin\MasterAdminController;
use App\Http\Controllers\Admin\MasterGoldPriceController;
use App\Http\Controllers\Admin\MasterGoldReadyStockController;
use App\Http\Controllers\Admin\MasterMitraKomisiController;
use App\Http\Controllers\Admin\MasterSettingController;
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

Route::get('/', function () {
    return view('front.home');
});

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

Route::middleware('auth')->prefix('customer')->group(function () {
    Route::get('/dashboard', function () {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->first();
        $orders = $customer
            ? \App\Models\TransPo::where('master_customer_id', $customer->id)->orderByDesc('id')->limit(10)->get()
            : collect();
        $readyOrders = $customer
            ? \App\Models\TransReady::where('master_customer_id', $customer->id)->orderByDesc('id')->limit(10)->get()
            : collect();
        $contracts = $customer
            ? \App\Models\TransCicilan::where('master_customer_id', $customer->id)->orderByDesc('id')->limit(10)->get()
            : collect();
        return view('front.customer.dashboard', compact('orders', 'readyOrders', 'contracts'));
    })->name('customer.dashboard');

    Route::get('/po/create', function () {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->first();
        return view('front.customer.po.create', compact('customer'));
    })->name('customer.po.create');

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

Route::get('/mitra/register', [MitraAuthController::class, 'showRegisterForm'])
    ->name('mitra.register');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('admin.login');

    Route::post('/admin/login', [AdminAuthController::class, 'login'])
        ->name('admin.login.submit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

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
