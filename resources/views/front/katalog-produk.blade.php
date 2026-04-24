<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Katalog Produk - Jajan Emas</title>
    <meta name="description" content="Katalog lengkap produk emas Jajan Emas. Tersedia berbagai brand dan gramasi dengan harga kompetitif.">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('template_home_front/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template_home_front/assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('template_home_front/assets/css/templatemo-lava.css') }}">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f5f5f5; margin: 0; }

        /* ===== Navbar ===== */
        .katalog-navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .katalog-navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .katalog-navbar .logo-img { height: 36px; }
        .katalog-nav-links { display: flex; align-items: center; gap: 24px; list-style: none; margin: 0; padding: 0; }
        .katalog-nav-links a { color: #555; font-size: 14px; font-weight: 500; text-decoration: none; transition: color .2s; }
        .katalog-nav-links a:hover { color: #dcb73f; }
        .nav-cart-btn { position: relative; }
        .nav-cart-btn .fa-shopping-cart { font-size: 20px; color: #555; }
        .nav-cart-badge {
            position: absolute; top: -8px; right: -10px;
            background: #e74c3c; color: #fff;
            font-size: 10px; font-weight: 700;
            min-width: 18px; height: 18px; line-height: 18px;
            text-align: center; border-radius: 50%;
            display: none;
        }
        .nav-cart-badge.has-items { display: inline-block; }

        /* ===== Page Layout ===== */
        .katalog-wrapper { display: flex; gap: 24px; padding: 24px 0; min-height: calc(100vh - 70px); }

        /* ===== Sidebar ===== */
        .katalog-sidebar {
            width: 240px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 12px;
            padding: 24px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
            align-self: flex-start;
            position: sticky;
            top: 80px;
        }
        .sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .sidebar-header h5 { margin: 0; font-size: 16px; font-weight: 700; color: #333; }
        .sidebar-reset { font-size: 13px; color: #dcb73f; text-decoration: none; font-weight: 600; }
        .sidebar-reset:hover { text-decoration: underline; }

        .filter-group { margin-bottom: 20px; }
        .filter-group h6 {
            font-size: 13px; font-weight: 700; color: #333;
            margin: 0 0 10px; padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        .filter-group label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #555; cursor: pointer;
            padding: 3px 0;
        }
        .filter-group input[type="checkbox"],
        .filter-group input[type="radio"] { accent-color: #dcb73f; margin: 0; }
        .filter-group .filter-scroll { max-height: 480px; overflow-y: auto; }

        /* ===== Main Content ===== */
        .katalog-main { flex: 1; min-width: 0; }

        .katalog-topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
        }
        .katalog-count { font-size: 14px; color: #666; }
        .katalog-count strong { color: #333; }

        .katalog-search {
            display: flex; align-items: center;
            background: #fff; border-radius: 8px;
            border: 1px solid #e0e0e0; overflow: hidden;
            flex: 1; max-width: 320px;
        }
        .katalog-search input {
            border: none; outline: none; padding: 8px 12px;
            font-size: 13px; flex: 1; font-family: 'Poppins', sans-serif;
        }
        .katalog-search button {
            background: none; border: none; padding: 8px 12px;
            color: #999; cursor: pointer;
        }

        .katalog-sort {
            position: relative;
        }
        .katalog-sort-btn {
            background: #fff; border: 1px solid #e0e0e0; border-radius: 8px;
            padding: 8px 14px; font-size: 13px; font-family: 'Poppins', sans-serif;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            color: #555; font-weight: 500;
        }
        .katalog-sort-btn i { font-size: 11px; }
        .katalog-sort-dropdown {
            position: absolute; top: 100%; right: 0; margin-top: 4px;
            background: #fff; border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,.12);
            min-width: 200px; z-index: 50;
            display: none; overflow: hidden;
        }
        .katalog-sort-dropdown.active { display: block; }
        .katalog-sort-dropdown a {
            display: block; padding: 10px 16px;
            font-size: 13px; color: #555; text-decoration: none;
            transition: background .2s;
        }
        .katalog-sort-dropdown a:hover,
        .katalog-sort-dropdown a.active { background: #dcb73f; color: #fff; }

        /* ===== Product Grid ===== */
        .katalog-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .k-card {
            background: #fff; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
            transition: transform .3s, box-shadow .3s;
            display: flex; flex-direction: column;
        }
        .k-card:hover { transform: translateY(-4px); box-shadow: 0 6px 24px rgba(0,0,0,.1); }

        .k-card-img {
            position: relative; width: 100%; height: 200px;
            overflow: hidden; background: #fafafa;
        }
        .k-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
        .k-card:hover .k-card-img img { transform: scale(1.05); }

        .k-card-badge {
            position: absolute; top: 10px; left: 10px;
            background: #dcb73f;
            color: #fff; font-size: 10px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: .4px;
        }
        .k-card-gramasi {
            position: absolute; top: 10px; right: 10px;
            background: rgba(0,0,0,.55); color: #fff;
            font-size: 10px; font-weight: 600;
            padding: 3px 8px; border-radius: 20px;
        }

        .k-card-body { padding: 14px 16px; flex: 1; display: flex; flex-direction: column; }
        .k-card-title {
            font-size: 13px; font-weight: 600; color: #333;
            margin: 0 0 6px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden; line-height: 1.4;
        }
        .k-card-info {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 11px; color: #999; margin-bottom: 8px;
        }
        .k-card-price { font-size: 17px; font-weight: 700; color: #dcb73f; margin-bottom: 4px; }
        .k-card-buyback { font-size: 11px; color: #27ae60; margin-bottom: 12px; }
        .k-card-buyback i { margin-right: 2px; }

        .k-card-footer { margin-top: auto; }
        .k-cart-btn {
            display: block; width: 100%; padding: 10px 0;
            border: none; border-radius: 25px;
            font-size: 13px; font-weight: 600; font-family: 'Poppins', sans-serif;
            cursor: pointer; text-align: center;
            transition: all .3s;
            background: #dcb73f;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .k-cart-btn:hover { background: #c9a636; }
        .k-cart-btn i { margin-right: 4px; }
        .k-cart-btn-disabled {
            background: #e8e8e8; color: #aaa; cursor: not-allowed;
        }
        .k-cart-btn-disabled:hover { opacity: 1; }

        /* ===== Pagination ===== */
        .katalog-pagination { margin-top: 30px; display: flex; justify-content: center; }
        .katalog-pagination .pagination { gap: 4px; }
        .katalog-pagination .page-link {
            border-radius: 8px; font-size: 13px; color: #555;
            border: 1px solid #e0e0e0; padding: 8px 14px;
        }
        .katalog-pagination .page-item.active .page-link {
            background: #dcb73f; border-color: #dcb73f; color: #fff;
        }

        /* ===== Empty State ===== */
        .katalog-empty {
            text-align: center; padding: 60px 20px; color: #aaa;
        }
        .katalog-empty i { font-size: 48px; margin-bottom: 16px; display: block; }

        /* ===== Mobile Filter Toggle ===== */
        .mobile-filter-toggle {
            display: none;
            background: #fff; border: 1px solid #e0e0e0; border-radius: 8px;
            padding: 8px 14px; font-size: 13px; font-family: 'Poppins', sans-serif;
            cursor: pointer; align-items: center; gap: 6px; color: #555; font-weight: 500;
        }

        /* ===== Cart Drawer ===== */
        .cart-drawer-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.4);
            z-index: 9998; opacity: 0; visibility: hidden; transition: all .3s;
        }
        .cart-drawer-overlay.active { opacity: 1; visibility: visible; }
        .cart-drawer {
            position: fixed; top: 0; right: -380px;
            width: 360px; max-width: 90vw; height: 100%;
            background: #fff; z-index: 9999;
            box-shadow: -4px 0 20px rgba(0,0,0,.15);
            transition: right .3s; display: flex; flex-direction: column;
        }
        .cart-drawer.active { right: 0; }
        .cart-drawer-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px; border-bottom: 1px solid #eee;
        }
        .cart-drawer-header h4 { margin: 0; font-size: 16px; font-weight: 700; color: #333; }
        .cart-drawer-header h4 i { margin-right: 8px; color: #dcb73f; }
        .cart-drawer-close { background: none; border: none; font-size: 22px; color: #999; cursor: pointer; }
        .cart-drawer-close:hover { color: #333; }
        .cart-drawer-body { flex: 1; overflow-y: auto; padding: 16px 20px; }
        .cart-drawer-empty { text-align: center; color: #aaa; padding: 40px 0; font-size: 14px; }
        .cart-drawer-empty i { display: block; font-size: 40px; margin-bottom: 12px; }
        .cart-item { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .cart-item-img { width: 56px; height: 56px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #f5f5f5; }
        .cart-item-img img { width: 100%; height: 100%; object-fit: cover; }
        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-name { font-size: 13px; font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cart-item-price { font-size: 13px; font-weight: 700; color: #dcb73f; margin-top: 2px; }
        .cart-item-qty { display: flex; align-items: center; gap: 8px; margin-top: 6px; }
        .cart-item-qty button {
            width: 24px; height: 24px; border: 1px solid #ddd; border-radius: 4px;
            background: #fafafa; cursor: pointer; font-size: 14px;
            display: flex; align-items: center; justify-content: center;
        }
        .cart-item-qty button:hover { background: #f0f0f0; }
        .cart-item-qty span { font-size: 13px; font-weight: 600; min-width: 20px; text-align: center; }
        .cart-item-remove { background: none; border: none; color: #e74c3c; font-size: 14px; cursor: pointer; align-self: flex-start; padding: 0; margin-top: 2px; }
        .cart-item-remove:hover { color: #c0392b; }
        .cart-drawer-footer { padding: 16px 20px; border-top: 1px solid #eee; }
        .cart-drawer-total { display: flex; justify-content: space-between; font-size: 15px; font-weight: 700; color: #333; margin-bottom: 12px; }
        .cart-drawer-total span:last-child { color: #dcb73f; }
        .cart-drawer-checkout {
            display: block; width: 100%; padding: 12px; border: none; border-radius: 8px;
            background: #dcb73f;
            color: #fff; font-size: 14px; font-weight: 700; cursor: pointer; text-align: center;
        }
        .cart-drawer-checkout:hover { opacity: .85; }

        /* ===== Responsive ===== */
        @media (max-width: 991px) {
            .katalog-grid { grid-template-columns: repeat(3, 1fr); gap: 16px; }
            .k-card-img { height: 170px; }
        }
        @media (max-width: 767px) {
            .katalog-sidebar {
                position: fixed; top: 0; left: -300px;
                width: 270px; height: 100%; z-index: 200;
                border-radius: 0; transition: left .3s;
                overflow-y: auto;
            }
            .katalog-sidebar.active { left: 0; }
            .sidebar-mobile-overlay {
                position: fixed; inset: 0; background: rgba(0,0,0,.4);
                z-index: 199; display: none;
            }
            .sidebar-mobile-overlay.active { display: block; }
            .mobile-filter-toggle { display: flex; }
            .katalog-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .k-card-img { height: 150px; }
            .k-card-body { padding: 10px 12px; }
            .k-card-title { font-size: 12px; }
            .k-card-price { font-size: 14px; }
            .k-card-info { font-size: 10px; }
            .k-cart-btn { font-size: 11px; padding: 8px 0; }
            .katalog-topbar { gap: 8px; }
            .katalog-search { max-width: 100%; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="katalog-navbar">
        <div class="container">
            <a href="/">
                <img src="{{ asset('assets/images/logo/logo-logotype.png') }}" alt="Jajan Emas" class="logo-img">
            </a>
            <ul class="katalog-nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/#about">About</a></li>
                <li><a href="/#contact-us">Contact</a></li>
                @auth
                    <li><a href="{{ route('customer.dashboard') }}"><i class="fa fa-user"></i> Dashboard</a></li>
                @else
                    <li><a href="{{ route('customer.login') }}">Login</a></li>
                @endauth
                <li>
                    <a href="#" class="nav-cart-btn" onclick="toggleCartDrawer(); return false;">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="nav-cart-badge" id="navCartBadge">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="katalog-wrapper">

            <!-- Sidebar Filter -->
            <div class="sidebar-mobile-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
            <aside class="katalog-sidebar" id="katalogSidebar">
                <div class="sidebar-header">
                    <h5><i class="fa fa-sliders"></i> Filter</h5>
                    <a href="{{ route('katalog.produk') }}" class="sidebar-reset">Reset</a>
                </div>

                <form id="filterForm" method="GET" action="{{ route('katalog.produk') }}">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <!-- Brand Filter -->
                    <div class="filter-group">
                        <h6>Brand</h6>
                        <div class="filter-scroll">
                            @foreach($allBrands as $brand)
                            <label>
                                <input type="checkbox" name="brand[]" value="{{ $brand }}"
                                    {{ in_array($brand, (array) request('brand', [])) ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                {{ $brand }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Gramasi Filter -->
                    <div class="filter-group">
                        <h6>Gramasi</h6>
                        <div class="filter-scroll">
                            @foreach($allGramasi as $gr)
                            <label>
                                <input type="checkbox" name="gramasi[]" value="{{ $gr }}"
                                    {{ in_array((string)$gr, array_map('strval', (array) request('gramasi', []))) ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                {{ rtrim(rtrim(number_format($gr, 3, ',', '.'), '0'), ',') }} gram
                            </label>
                            @endforeach
                        </div>
                    </div>
                </form>
            </aside>

            <!-- Main Content -->
            <main class="katalog-main">
                <div class="katalog-topbar">
                    <button class="mobile-filter-toggle" onclick="toggleSidebar()">
                        <i class="fa fa-sliders"></i> Filter
                    </button>

                    <span class="katalog-count">Menampilkan <strong>{{ $products->total() }}</strong> produk</span>

                    <form class="katalog-search" method="GET" action="{{ route('katalog.produk') }}">
                        @foreach((array) request('brand', []) as $b)
                            <input type="hidden" name="brand[]" value="{{ $b }}">
                        @endforeach
                        @foreach((array) request('gramasi', []) as $g)
                            <input type="hidden" name="gramasi[]" value="{{ $g }}">
                        @endforeach
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk...">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>

                    <div class="katalog-sort">
                        <button class="katalog-sort-btn" onclick="document.getElementById('sortDropdown').classList.toggle('active')">
                            <i class="fa fa-sort-amount-desc"></i> Urutkan
                        </button>
                        <div class="katalog-sort-dropdown" id="sortDropdown">
                            @php $currentSort = request('sort', 'terbaru'); @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}" class="{{ $currentSort === 'terbaru' ? 'active' : '' }}">Terbaru</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'harga_asc']) }}" class="{{ $currentSort === 'harga_asc' ? 'active' : '' }}">Harga: Rendah ke Tinggi</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'harga_desc']) }}" class="{{ $currentSort === 'harga_desc' ? 'active' : '' }}">Harga: Tinggi ke Rendah</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_asc']) }}" class="{{ $currentSort === 'nama_asc' ? 'active' : '' }}">Nama: A ke Z</a>
                        </div>
                    </div>
                </div>

                @if($products->count())
                <div class="katalog-grid">
                    @foreach($products as $product)
                    @php
                        $images = $product->images ?? [];
                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                        $imageUrl = $firstImage
                            ? (str_starts_with($firstImage, 'http') ? $firstImage : asset($firstImage))
                            : asset('assets/images/no-image.png');
                        $harga = (int) ($product->harga_jual_fix ?? 0);
                        $buyback = (int) ($product->harga_jual_minimal ?? 0);
                        $stok = (int) ($product->stok ?? 0);
                    @endphp
                    <div class="k-card">
                        <div class="k-card-img">
                            <img src="{{ $imageUrl }}" alt="{{ $product->nama_produk ?? $product->brand }}" loading="lazy">
                            @if($product->brand)
                            <span class="k-card-badge">{{ $product->brand }}</span>
                            @endif
                            @if($product->gramasi)
                            <span class="k-card-gramasi">{{ rtrim(rtrim(number_format($product->gramasi, 3, ',', '.'), '0'), ',') }}g</span>
                            @endif
                        </div>
                        <div class="k-card-body">
                            <h5 class="k-card-title">{{ $product->nama_produk ?? $product->brand . ' ' . $product->gramasi . 'gr' }}</h5>
                            <div class="k-card-info">
                                <span>{{ rtrim(rtrim(number_format($product->gramasi ?? 0, 3, ',', '.'), '0'), ',') }}g</span>
                                <span>Stok: {{ $stok }}</span>
                            </div>
                            <div class="k-card-price">Rp {{ number_format($harga, 0, ',', '.') }}</div>
                            @if($buyback > 0)
                            <div class="k-card-buyback"><i class="fa fa-exchange"></i> buyback Rp {{ number_format($buyback, 0, ',', '.') }}</div>
                            @endif
                            <div class="k-card-footer">
                                @if($stok >= 1)
                                <button class="k-cart-btn" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->nama_produk ?? $product->brand) }}', {{ $harga }}, '{{ $imageUrl }}', {{ $stok }})">
                                    <i class="fa fa-cart-plus"></i> + Keranjang
                                </button>
                                @else
                                <button class="k-cart-btn k-cart-btn-disabled" disabled>
                                    <i class="fa fa-times-circle"></i> Stok Kosong
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="katalog-pagination">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
                @else
                <div class="katalog-empty">
                    <i class="fa fa-search"></i>
                    <p>Tidak ada produk ditemukan.</p>
                    <a href="{{ route('katalog.produk') }}" style="color:#dcb73f; font-weight:600;">Reset Filter</a>
                </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Cart Drawer -->
    <div class="cart-drawer-overlay" id="cartOverlay" onclick="toggleCartDrawer()"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-drawer-header">
            <h4><i class="fa fa-shopping-cart"></i> Keranjang</h4>
            <button class="cart-drawer-close" onclick="toggleCartDrawer()">&times;</button>
        </div>
        <div class="cart-drawer-body" id="cartDrawerBody">
            <div class="cart-drawer-empty"><i class="fa fa-shopping-basket"></i>Keranjang masih kosong</div>
        </div>
        <div class="cart-drawer-footer" id="cartDrawerFooter" style="display:none;">
            <div class="cart-drawer-total">
                <span>Total</span>
                <span id="cartDrawerTotal">Rp 0</span>
            </div>
            <button class="cart-drawer-checkout" onclick="alert('Silakan login untuk melanjutkan checkout')">
                <i class="fa fa-arrow-right"></i> Checkout
            </button>
        </div>
    </div>

    <script src="{{ asset('template_home_front/assets/js/jquery-2.1.0.min.js') }}"></script>
    <script src="{{ asset('template_home_front/assets/js/bootstrap.min.js') }}"></script>

    <script>
    // Sidebar toggle (mobile)
    function toggleSidebar() {
        document.getElementById('katalogSidebar').classList.toggle('active');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    // Close sort dropdown on outside click
    document.addEventListener('click', function(e) {
        var dd = document.getElementById('sortDropdown');
        if (!e.target.closest('.katalog-sort')) dd.classList.remove('active');
    });

    // Cart (shared localStorage with home page)
    (function(){
        var CART_KEY = 'jj_cart';
        function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch(e) { return []; } }
        function saveCart(c) { localStorage.setItem(CART_KEY, JSON.stringify(c)); }
        function formatRp(n) { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

        window.addToCart = function(id, name, price, image, maxStok) {
            var cart = getCart(), found = false;
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].id === id) { if (cart[i].qty < maxStok) cart[i].qty++; found = true; break; }
            }
            if (!found) cart.push({ id:id, name:name, price:price, image:image, qty:1, maxStok:maxStok });
            saveCart(cart); renderCart();
            document.getElementById('cartOverlay').classList.add('active');
            document.getElementById('cartDrawer').classList.add('active');
            document.body.style.overflow = 'hidden';
        };

        window.toggleCartDrawer = function() {
            var o = document.getElementById('cartOverlay'), d = document.getElementById('cartDrawer');
            if (d.classList.contains('active')) { o.classList.remove('active'); d.classList.remove('active'); document.body.style.overflow = ''; }
            else { o.classList.add('active'); d.classList.add('active'); document.body.style.overflow = 'hidden'; }
        };

        window.changeQty = function(id, delta) {
            var cart = getCart();
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].id === id) { cart[i].qty += delta; if (cart[i].qty <= 0) cart.splice(i,1); else if (cart[i].qty > cart[i].maxStok) cart[i].qty = cart[i].maxStok; break; }
            }
            saveCart(cart); renderCart();
        };

        window.removeFromCart = function(id) {
            saveCart(getCart().filter(function(i){ return i.id !== id; })); renderCart();
        };

        function renderCart() {
            var cart = getCart(), body = document.getElementById('cartDrawerBody'),
                footer = document.getElementById('cartDrawerFooter'), badge = document.getElementById('navCartBadge'),
                tc = 0, tp = 0;
            for (var i = 0; i < cart.length; i++) { tc += cart[i].qty; tp += cart[i].qty * cart[i].price; }
            if (tc > 0) { badge.textContent = tc > 99 ? '99+' : tc; badge.classList.add('has-items'); }
            else { badge.classList.remove('has-items'); }
            if (!cart.length) { body.innerHTML = '<div class="cart-drawer-empty"><i class="fa fa-shopping-basket"></i>Keranjang masih kosong</div>'; footer.style.display = 'none'; return; }
            var h = '';
            for (var i = 0; i < cart.length; i++) {
                var c = cart[i];
                h += '<div class="cart-item"><div class="cart-item-img"><img src="'+c.image+'" alt="'+c.name+'"></div>'
                    +'<div class="cart-item-info"><div class="cart-item-name">'+c.name+'</div><div class="cart-item-price">'+formatRp(c.price)+'</div>'
                    +'<div class="cart-item-qty"><button onclick="changeQty('+c.id+',-1)">−</button><span>'+c.qty+'</span><button onclick="changeQty('+c.id+',1)">+</button></div></div>'
                    +'<button class="cart-item-remove" onclick="removeFromCart('+c.id+')"><i class="fa fa-trash"></i></button></div>';
            }
            body.innerHTML = h; footer.style.display = 'block';
            document.getElementById('cartDrawerTotal').textContent = formatRp(tp);
        }
        renderCart();
    })();
    </script>
</body>
</html>
