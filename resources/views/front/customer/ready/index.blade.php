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
    <title>Emas Ready || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.dashboard') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Emas Ready</h3>
    </div>
    <div class="app-content style-3">
        <div class="tf-container">

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="p-2">
                @forelse ($stocks as $s)
                    <div class="box-app">
                        <div class="info-box">
                            <a href="{{ route('customer.ready.stock', ['stock' => encrypt((string)$s->id)]) }}" class="logo">
                                <img src="{{ asset('front/images/golds/antam_2.jpg') }}" alt="logo">
                            </a>
                            <div class="content">
                                <div class="h7 text-dark">
                                    <a href="{{ route('customer.ready.stock', ['stock' => encrypt((string)$s->id)]) }}">
                                        {{ strtoupper($s->brand) }} {{ number_format((float) ($s->gramasi ?? 0), 3) }} gr
                                    </a>
                                    <span class="dot"></span>
                                    <span class="body-6 text-dark-4">Harga: Rp {{ number_format((float) ($s->harga_jual_fix ?? $s->harga_jual_minimal ?? 0), 0) }}</span>
                                </div>
                                <div class="box-map-date">
                                    <div class="d-flex gap-4 align-items-center">
                                        <i class="icon icon-wallet-2 text-primary"></i>
                                        <span class="body-3 text-dark-4">Kode: {{ $s->kode_item }}</span>
                                    </div>
                                    <div class="d-flex gap-4 align-items-center">
                                        <i class="icon icon-map text-secondary-yellow"></i>
                                        <span class="body-3 text-dark-4">Status: Ready</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-btn">
                            <a href="{{ route('customer.ready.stock', ['stock' => encrypt((string)$s->id)]) }}" class="btn-app button-1">Detail</a>
                            <a href="{{ route('customer.ready.buy', ['stock' => encrypt((string)$s->id)]) }}" class="btn-app button-1 view-app">Beli</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">Belum ada stok tersedia.</div>
                @endforelse
            </div>
            <div class="p-2">
                {{ $stocks->links() }}
            </div>
        </div>
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