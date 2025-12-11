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
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;

use App\Http\Controllers\Front\CustomerAuthController;
use App\Http\Controllers\Front\MitraAuthController;
use App\Http\Controllers\Front\CustomerPoController;
use App\Http\Controllers\Front\CustomerReadyController;
use App\Http\Controllers\Front\CustomerCicilanController;
use App\Http\Controllers\Front\FrontController;

// ====================================
// FRONT HOME
// ====================================
Route::get('/', [FrontController::class, 'home']);
Route::get('/pemesanan-emas-belum-tersedia', function () {
    return view('front.order-unavailable');
})->name('order.unavailable');

// ====================================
// CUSTOMER AREA
// ====================================
Route::prefix('customer')->name('customer.')->group(function () {
    // Auth
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');

    Route::get('/forgot-password', [FrontController::class, 'customerForgotPassword'])
        ->name('forgot-password');
    Route::post('/forgot-password', [CustomerAuthController::class, 'sendResetLink'])
        ->name('forgot-password.submit');

    // Protected (harus login)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [FrontController::class, 'customerDashboard'])->name('dashboard');
        Route::get('/product-dan-layanan', [FrontController::class, 'customerProductDanLayanan'])->name('product-dan-layanan');
        Route::get('/profile', [FrontController::class, 'customerProfile'])->name('profile');
        Route::put('/profile', [FrontController::class, 'updateCustomerProfile'])->name('profile.update');
        Route::get('/all-order', [FrontController::class, 'customerAllOrders'])->name('all-order');

        // PO / Pre-order emas
        Route::get('/po/create', [FrontController::class, 'customerPoCreate'])->name('po.create');
        Route::get('/pre-order-emas', [FrontController::class, 'customerPoCreate'])->name('pre-order-emas');

        Route::post('/po', [CustomerPoController::class, 'store'])->name('po.store');
        Route::get('/po/{po}', [CustomerPoController::class, 'show'])->name('po.show');
        Route::post('/po/{po}/confirm-payment', [CustomerPoController::class, 'confirmPayment'])->name('po.confirm-payment');

        // Ready
        Route::get('/ready', [CustomerReadyController::class, 'index'])->name('ready.index');
        Route::get('/ready/{stock}', [CustomerReadyController::class, 'stock'])->name('ready.stock');
        Route::get('/ready/{stock}/buy', [CustomerReadyController::class, 'buy'])->name('ready.buy');
        Route::post('/ready', [CustomerReadyController::class, 'store'])->name('ready.store');
        Route::get('/ready-trans/{ready}', [CustomerReadyController::class, 'show'])->name('ready.show');
        Route::post('/ready-trans/{ready}/confirm-payment', [CustomerReadyController::class, 'confirmPayment'])->name('ready.confirm-payment');

        // Cicilan
        Route::get('/cicilan', [CustomerCicilanController::class, 'index'])->name('cicilan.index');
        Route::get('/cicilan/{stock}', [CustomerCicilanController::class, 'stock'])->name('cicilan.stock');
        Route::post('/cicilan', [CustomerCicilanController::class, 'store'])->name('cicilan.store');
        Route::get('/cicilan-kontrak/{contract}', [CustomerCicilanController::class, 'show'])->name('cicilan.show');
        Route::post('/cicilan-payment/{payment}/confirm-payment', [CustomerCicilanController::class, 'confirmPayment'])->name('cicilan.confirm-payment');

        // Logout
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    });
});

// Alias /login → customer login (tetap ada)
Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');

// Password reset routes (global name untuk notifikasi default Laravel)
Route::get('/customer/reset-password/{token}', [CustomerAuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/customer/reset-password', [CustomerAuthController::class, 'resetPassword'])->name('password.update');

// ====================================
// MITRA AREA
// ====================================
Route::prefix('mitra')->name('mitra.')->group(function () {
    Route::get('/login', [MitraAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [MitraAuthController::class, 'login'])->name('login.submit');

    // Route::get('/register', [MitraAuthController::class, 'showRegisterForm'])->name('register');
    // Route::post('/register', [MitraAuthController::class, 'register'])->name('register.submit');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [FrontController::class, 'mitraDashboard'])->name('dashboard');
        Route::get('/komisi', [FrontController::class, 'mitraKomisiIndex'])->name('komisi.index');
        Route::get('/profile', [FrontController::class, 'mitraProfile'])->name('profile');
        Route::put('/profile', [FrontController::class, 'updateMitraProfile'])->name('profile.update');

        Route::post('/logout', [MitraAuthController::class, 'logout'])->name('logout');
    });
});

// ====================================
// ADMIN AUTH (GUEST)
// ====================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // ====================================
    // ADMIN DASHBOARD (admin_or_agen)
    // ====================================
    Route::middleware(['auth', 'admin_or_agen'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });

    // ====================================
    // ADMIN ONLY (middleware: admin)
    // ====================================
    Route::middleware(['auth', 'admin_or_agen'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA (prefix: /admin/master)
        |--------------------------------------------------------------------------
        */
        Route::prefix('master')->name('master.')->group(function () {

            // Customers (tanpa show) — param {customer}
            Route::resource('customers', MasterCustomerController::class)
                ->except(['show'])
                ->names('customers')
                ->parameters([
                    'customers' => 'customer',
                ]);

            // Mitra Brankas — param {mitra}
            Route::resource('mitra-brankas', MitraBrankasController::class)
                ->except(['show'])
                ->names('mitra-brankas')
                ->parameters([
                    'mitra-brankas' => 'mitra',
                ]);

            // Agens — param {agen}
            Route::resource('agens', MasterAgenController::class)
                ->except(['show'])
                ->names('agens')
                ->parameters([
                    'agens' => 'agen',
                ])
                ->middleware('admin');

            // Admins — param {admin}
            Route::resource('admins', MasterAdminController::class)
                ->except(['show'])
                ->names('admins')
                ->parameters([
                    'admins' => 'admin',
                ])
                ->middleware('admin');

            // Brand Emas — param {brand}
            Route::resource('brand-emas', MasterBrandEmasController::class)
                ->except(['show'])
                ->names('brand-emas')
                ->parameters([
                    'brand-emas' => 'brand',
                ]);

            // Home Sliders — param {slider}
            Route::resource('home-slider', MasterHomeSliderController::class)
                ->except(['show'])
                ->names('home-slider')
                ->parameters([
                    'home-slider' => 'slider',
                ])->middleware('admin');

            // Menu Home Customer — param {menu}
            Route::resource('menu-home-customer', MasterMenuHomeCustomerController::class)
                ->except(['show'])
                ->names('menu-home-customer')
                ->parameters([
                    'menu-home-customer' => 'menu',
                ])->middleware('admin');

            // Produk & Layanan — param {item}
            Route::resource('produk-layanan', MasterProdukDanLayananController::class)
                ->except(['show'])
                ->names('produk-layanan')
                ->parameters([
                    'produk-layanan' => 'item',
                ]);

            // Gramasi Emas — param {item}
            Route::resource('gramasi-emas', MasterGramasiEmasController::class)
                ->except(['show'])
                ->names('gramasi-emas')
                ->parameters([
                    'gramasi-emas' => 'item',
                ]);

            // Gold Prices — param {price}
            Route::resource('gold-prices', MasterGoldPriceController::class)
                ->except(['show'])
                ->names('gold-prices')
                ->parameters([
                    'gold-prices' => 'price',
                ]);

            // Ready Stocks — param {stock}
            Route::resource('ready-stocks', MasterGoldReadyStockController::class)
                ->except(['show'])
                ->names('ready-stocks')
                ->parameters([
                    'ready-stocks' => 'stock',
                ]);

            // Mitra Komisi — param {komisi}
            Route::resource('mitra-komisi', MasterMitraKomisiController::class)
                ->except(['show'])
                ->names('mitra-komisi')
                ->parameters([
                    'mitra-komisi' => 'komisi',
                ])
                ->middleware('admin');


            // Settings — param {setting}
            Route::resource('settings', MasterSettingController::class)
                ->except(['show'])
                ->names('settings')
                ->parameters([
                    'settings' => 'setting',
                ])
                ->middleware('admin');
        });

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI (prefix: /admin/trans)
        |--------------------------------------------------------------------------
        */
        Route::prefix('trans')->name('trans.')->group(function () {

            // Payment Logs (index, show, approve, reject)
            Route::get('/payment-logs', [TransPaymentLogController::class, 'index'])
                ->name('payment-logs.index');
            Route::get('/payment-logs/{log}', [TransPaymentLogController::class, 'show'])
                ->name('payment-logs.show');
            Route::post('/payment-logs/{log}/approve', [TransPaymentLogController::class, 'approve'])
                ->name('payment-logs.approve');
            Route::post('/payment-logs/{log}/reject', [TransPaymentLogController::class, 'reject'])
                ->name('payment-logs.reject');

            // PO
            Route::prefix('po')->name('po.')->group(function () {
                Route::get('/', [TransPoController::class, 'index'])->name('index');
                Route::get('/{po}', [TransPoController::class, 'show'])->name('show');
                Route::post('/{po}/approve-payment', [TransPoController::class, 'approvePayment'])->name('approve-payment');
                Route::post('/{po}/reject-payment', [TransPoController::class, 'rejectPayment'])->name('reject-payment');
                Route::post('/{po}/status', [TransPoController::class, 'updateStatus'])->name('update-status');
                Route::post('/cancel-pending', [TransPoController::class, 'cancelPendingAll'])->name('cancel-pending-all');

                // Mitra Komisi assign/remove (nama & URL sama seperti awal)
                Route::post('{po}/mitra-komisi', [\App\Http\Controllers\Admin\TransPoMitraKomisiController::class, 'store'])
                    ->name('mitra-komisi.store');
                Route::delete('mitra-komisi/{assignment}', [\App\Http\Controllers\Admin\TransPoMitraKomisiController::class, 'destroy'])
                    ->name('mitra-komisi.destroy');
            });

            // Cicilan
            Route::get('/cicilan', [TransCicilanController::class, 'index'])
                ->name('cicilan.index');
            Route::get('/cicilan/{contract}', [TransCicilanController::class, 'show'])
                ->name('cicilan.show');

            // Ready
            Route::get('/ready', [TransReadyController::class, 'index'])
                ->name('ready.index');
            Route::get('/ready/{ready}', [TransReadyController::class, 'show'])
                ->name('ready.show');
            Route::post('/ready/{ready}/status', [TransReadyController::class, 'updateStatus'])
                ->name('ready.update-status');

            // Cicilan Payments
            Route::get('/cicilan-payments', [TransCicilanPaymentController::class, 'index'])
                ->name('cicilan-payments.index');
            Route::get('/cicilan-payments/{payment}', [TransCicilanPaymentController::class, 'show'])
                ->name('cicilan-payments.show');
        });

        /*
        |--------------------------------------------------------------------------
        | ROLE & PERMISSION MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('permissions')->name('permissions.')->middleware('admin')->group(function () {
            Route::get('/users', [PermissionController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/edit', [PermissionController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [PermissionController::class, 'update'])->name('users.update');
        });

        Route::resource('roles', RoleController::class)
            ->names('roles')
            ->middleware('admin');
    });
});
