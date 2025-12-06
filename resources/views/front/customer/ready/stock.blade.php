<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css')}}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css')}}" />
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png')}}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png')}}" />
    <title>Detail Stok Ready || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.ready.index') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Detail Stok</h3>
    </div>
    <div class="app-content style-3">
        <div class="tf-container">

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h6 mb-3">Detail Stok Emas Ready</h1>

            <div class="row g-3">
                <div class="col-md-4"><strong>Kode Item</strong><br>{{ $stock->kode_item }}</div>
                <div class="col-md-4"><strong>Brand</strong><br>{{ strtoupper($stock->brand) }}</div>
                <div class="col-md-4"><strong>Gramasi</strong><br>{{ number_format((float)$stock->gramasi, 3, ',', '.') }} g</div>
                <div class="col-md-4"><strong>Kondisi</strong><br>{{ strtoupper($stock->kondisi_barang) }}</div>
                <div class="col-md-4"><strong>Status</strong><br>{{ strtoupper($stock->status) }}</div>
                <div class="col-md-4"><strong>Harga Jual</strong><br>{{ number_format((float)($stock->harga_jual_fix ?? $stock->harga_jual_minimal ?? 0), 2, ',', '.') }} IDR</div>
                <div class="col-md-12"><strong>Lokasi Simpan</strong><br>{{ $stock->lokasi_simpan ?? '-' }}</div>
                <div class="col-md-12"><strong>Catatan</strong><br>{{ $stock->catatan ?? '-' }}</div>
            </div>

            <hr>
            <div class="mb-2 text-muted">
                Jika Anda membeli dengan metode pengiriman, formulir akan otomatis terisi dengan data profil Anda:
                {{ $customer->full_name ?? '-' }}, {{ $customer->phone_wa ?? '-' }},
                {{ $customer->address_line ?? '-' }}, {{ $customer->kota ?? '-' }}, {{ $customer->provinsi ?? '-' }}, {{ $customer->kode_pos ?? '-' }}.
            </div>
            <a href="{{ route('customer.ready.buy', ['stock' => encrypt((string)$stock->id)]) }}" class="btn btn-success">Lanjut Beli</a>
        </div>
    </div>
        </div>
    </div>
    @include('front.customer.partials.menubar-footer', ['active' => 'produk'])
    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
</body>
</html>