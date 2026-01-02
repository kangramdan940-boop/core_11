<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin - Jasa Emas')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body { background-color: #f3f4f6; }
        .admin-wrapper { min-height: 100vh; display: flex; flex-direction: row; }
        .admin-sidebar { width: 260px; background-color: #0f172a; color: #e5e7eb; }
        .admin-sidebar .brand { padding: 16px 20px; font-weight: 700; font-size: 1.1rem; border-bottom: 1px solid rgba(148,163,184,.4); }
        .admin-sidebar a { color: #e5e7eb; text-decoration: none; display: block; padding: 10px 18px; font-size: 0.9rem; }
        .admin-sidebar a.nav-link { margin: 2px 12px; border-radius: 8px; }
        .admin-sidebar a.nav-link.active { background-color: #1f2937; color: #ffffff; }
        .admin-sidebar a:hover { background-color: #111827; }
        .admin-sidebar .menu-title, .nav-section-title { padding: 12px 18px 6px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: .08em; color: #9ca3af; }
        .admin-content { flex: 1; display: flex; flex-direction: column; }
        .admin-topbar { background-color: #ffffff; border-bottom: 1px solid #e5e7eb; }
        .admin-topbar .user-name { font-size: 0.9rem; margin-right: 10px; }
        .admin-main { padding: 20px; }
        .sidebar-hidden { display: none; }
        @media (max-width: 768px) { .admin-sidebar { display: none; } }
    </style>

    @stack('styles')
</head>
<body>

<div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="brand">
            Jasa Emas<br>
            <small style="font-size: 0.75rem; color:#9ca3af;">Admin Panel</small>
        </div>

        <div class="menu-title">Main</div>
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <div class="menu-title">Transaksi</div>
        <a class="nav-link {{ request()->routeIs('admin.master.produk-layanan.*') ? 'active' : '' }}" href="{{ route('admin.master.produk-layanan.index') }}">🛍️ Produk & Layanan</a>
        <a class="nav-link {{ request()->routeIs('admin.trans.po.*') ? 'active' : '' }}" href="{{ route('admin.trans.po.index') }}">📜 PO Emas</a>
        <a class="nav-link {{ request()->routeIs('admin.trans.ready.*') ? 'active' : '' }}" href="{{ route('admin.trans.ready.index') }}">⚡ Emas Ready</a>
        <a class="nav-link {{ request()->routeIs('admin.trans.cicilan.*') ? 'active' : '' }}" href="{{ route('admin.trans.cicilan.index') }}">📆 Cicilan Emas</a>
        <a class="nav-link {{ request()->routeIs('admin.trans.cicilan-payments.*') ? 'active' : '' }}" href="{{ route('admin.trans.cicilan-payments.index') }}">💳 Pembayaran Cicilan</a>
        <a class="nav-link {{ request()->routeIs('admin.trans.payment-logs.*') ? 'active' : '' }}" href="{{ route('admin.trans.payment-logs.index') }}">📑 Payment Log</a>
        <a class="nav-link {{ request()->routeIs('admin.trans.mitra-withdrawals.*') ? 'active' : '' }}" href="{{ route('admin.trans.mitra-withdrawals.index') }}">🏦 WD Mitra</a>
 <div class="menu-title">Emas</div>
        <a class="nav-link {{ request()->routeIs('admin.master.ready-stocks.*') ? 'active' : '' }}" href="{{ route('admin.master.ready-stocks.index') }}">📦 Stok Emas Ready</a>

        @if(auth()->user() && auth()->user()->role === 'super_admin')
        <a class="nav-link {{ request()->routeIs('admin.master.gold-prices.*') ? 'active' : '' }}" href="{{ route('admin.master.gold-prices.index') }}">📊 Harga Emas</a>
        <a class="nav-link {{ request()->routeIs('admin.master.mitra-komisi.*') ? 'active' : '' }}" href="{{ route('admin.master.mitra-komisi.index') }}">💰 Komisi Mitra</a>
        @endif

        <div class="menu-title">Master</div>
        <a class="nav-link {{ request()->routeIs('admin.master.customers.*') ? 'active' : '' }}" href="{{ route('admin.master.customers.index') }}">👤 Customer</a>
        <a class="nav-link {{ request()->routeIs('admin.master.brand-emas.*') ? 'active' : '' }}" href="{{ route('admin.master.brand-emas.index') }}">🏷️ Brand Emas</a>
        <a class="nav-link {{ request()->routeIs('admin.master.gramasi-emas.*') ? 'active' : '' }}" href="{{ route('admin.master.gramasi-emas.index') }}">⚖️ Gramasi Emas</a>
        @if(auth()->user() && auth()->user()->role === 'super_admin')
        <a class="nav-link {{ request()->routeIs('admin.master.home-slider.*') ? 'active' : '' }}" href="{{ route('admin.master.home-slider.index') }}">🖼️ Home Slider</a>
        <a class="nav-link {{ request()->routeIs('admin.master.menu-home-customer.*') ? 'active' : '' }}" href="{{ route('admin.master.menu-home-customer.index') }}">📱 Menu Home Customer</a>
        @endif
        @if(auth()->user() && auth()->user()->role === 'super_admin')
            <a class="nav-link {{ request()->routeIs('admin.master.agens.*') ? 'active' : '' }}" href="{{ route('admin.master.agens.index') }}">🧑‍💼 Agen</a>
            <a class="nav-link {{ request()->routeIs('admin.master.admins.*') ? 'active' : '' }}" href="{{ route('admin.master.admins.index') }}">🛡️ Admin</a>
            <a class="nav-link {{ request()->routeIs('admin.master.settings.*') ? 'active' : '' }}" href="{{ route('admin.master.settings.index') }}">⚙️ Setting</a>
        @endif
        <a class="nav-link {{ request()->routeIs('admin.master.mitra-brankas.*') ? 'active' : '' }}" href="{{ route('admin.master.mitra-brankas.index') }}">🏦 Mitra Brankas</a>
       

       

        <div class="menu-title">System</div>
        @if(auth()->user() && auth()->user()->role === 'super_admin')
            <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.users.index') }}">🔐 Hak Akses</a>
            <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">🛡️ Roles</a>
        @endif
        <a class="nav-link {{ request()->routeIs('admin.login-management.*') ? 'active' : '' }}" href="{{ route('admin.login-management.index') }}">👥 Management Login List</a>
        <a class="nav-link" href="#">🔔 Notifikasi</a>
    </aside>

    <!-- CONTENT -->
    <div class="admin-content">
        <!-- TOPBAR -->
        <nav class="admin-topbar navbar navbar-light px-3">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-secondary d-md-none me-2" id="sidebarToggle">Menu</button>
                    <span class="navbar-brand mb-0 h6">@yield('page_title', 'Dashboard')</span>
                </div>
                <div class="d-flex align-items-center">
                    @auth
                        <span class="user-name text-muted me-2">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                        <form action="{{ route('admin.logout') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="admin-main">
            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap JS (opsional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.querySelector('.admin-sidebar')?.classList.toggle('sidebar-hidden');
    });
</script>
@stack('scripts')
</body>
</html>