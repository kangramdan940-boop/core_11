<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jajan Emas - beli emas dengan mudah</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <!-- font -->
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css') }}" />
    <!-- manifest json -->
    <link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}" />
    <script>
        if (localStorage.toggled === "dark-theme") {
            document.documentElement.classList.add('dark-theme');
        }
    </script>

</head>

<body>
    <div class="preload preload-container">
        <div class="logo-img">
            <img src="{{ asset('front/images/logo/logo-dark2.png') }}" alt="">
        </div>
        <div class="spinner-circle lg success">
            <span class="spinner-circle1 spinner-child"></span>
            <span class="spinner-circle2 spinner-child"></span>
            <span class="spinner-circle3 spinner-child"></span>
            <span class="spinner-circle4 spinner-child"></span>
            <span class="spinner-circle5 spinner-child"></span>
            <span class="spinner-circle6 spinner-child"></span>
            <span class="spinner-circle7 spinner-child"></span>
            <span class="spinner-circle8 spinner-child"></span>
            <span class="spinner-circle9 spinner-child"></span>
        </div>
    </div>
    <!-- /preload -->
    <div class="header-avt fixed-top">
        <a href="setup-profile" class="box-avt">
            <div class="content">
                <span class="body-4 text-dark-4">Welcome back!</span>
                <h4 class="name">
                    {{ $customer ? $customer->full_name : (auth()->user()->name ?? '') }}
                </h4>
            </div>
        </a>

        <div class="right">
           
            <!-- <a class="box-icon" href="#notification" data-bs-toggle="offcanvas" aria-controls="offcanvasBottom">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 6.44043V9.77043" stroke="#1A1528" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" />
                    <path
                        d="M12.0199 2C8.3399 2 5.3599 4.98 5.3599 8.66V10.76C5.3599 11.44 5.0799 12.46 4.7299 13.04L3.4599 15.16C2.6799 16.47 3.2199 17.93 4.6599 18.41C9.4399 20 14.6099 20 19.3899 18.41C20.7399 17.96 21.3199 16.38 20.5899 15.16L19.3199 13.04C18.9699 12.46 18.6899 11.43 18.6899 10.76V8.66C18.6799 5 15.6799 2 12.0199 2Z"
                        stroke="#1A1528" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                    <path
                        d="M15.3299 18.8203C15.3299 20.6503 13.8299 22.1503 11.9999 22.1503C11.0899 22.1503 10.2499 21.7703 9.64992 21.1703C9.04992 20.5703 8.66992 19.7303 8.66992 18.8203"
                        stroke="#1A1528" stroke-width="1.5" stroke-miterlimit="10" />
                </svg>
            </a> -->
                <a href="#" class="box-icon logout-btn" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" title="Logout">
                    <svg width="24" height="24" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 3h5a1 1 0 011 1v9a1 1 0 01-1 1H6" stroke="#1A1528" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 8.5h6M5 6l-2.5 2.5L5 11" stroke="#1A1528" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <form id="logoutForm" action="{{ route('customer.logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
        </div>
    </div>
    <div class="app-content style-2">
        <div class="tf-container">
             <div class="brankas-card" style="display:flex;border-radius:16px;overflow:hidden;background:#0d1f36;height:240px;">
                <div style="flex:1;background:#0b1a2d;color:#fff;padding:16px;display:flex;flex-direction:column;justify-content:space-between;">
                    <div>
                        <div style="margin-top:6px;font-size:14px;">{{ $customer->phone_wa ?? (auth()->user()->email ?? '') }}</div>
                        <div style="font-size:14px;">{{ $customer?->full_name ?? (auth()->user()->name ?? '') }}</div>

                        <div style="display:flex;gap:32px;margin-top:12px;">
                            <div>
                                <div style="opacity:.8;font-size:12px;">PO Emas</div>
                                <div style="font-size:14px;">{{ number_format((float)$poGramTotal, 3, ',', '.') }} gr</div>
                            </div>
                            <div>
                                <div style="opacity:.8;font-size:12px;">Emas Ready</div>
                                <div style="font-size:14px;">{{ number_format((float)$readyGramTotal, 3, ',', '.') }} gr</div>
                            </div>
                        </div>
                        <div style="margin-top:12px;">
                            <div style="opacity:.8;font-size:12px;">Cicilan Emas</div>
                            <div style="font-size:14px;">{{ number_format((float)$cicilanGramTotal, 3, ',', '.') }} gr</div>
                        </div>
                    </div>
                    <div style="margin-top:8px;border-top:1px solid rgba(255,255,255,.4);padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                        <div style="font-size:16px;font-weight:600;">Saldo 1.0 gr</div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7z" stroke="white" stroke-width="1.5" />
                            <circle cx="12" cy="12" r="3" stroke="white" stroke-width="1.5" />
                        </svg>
                    </div>
                </div>
                <div style="width:40%;background:linear-gradient(135deg,#c89b3c,#f2d47a);position:relative;">
                    <div style="position:absolute;top:24px;left:-40px;width:220px;height:220px;border-radius:50%;background:rgba(0,0,0,.35);"></div>
                    <div style="position:absolute;top:40px;right:24px;width:120px;height:120px;border-radius:16px;background:rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;">
                        <div style="width:88px;height:88px;border-radius:50%;border:6px solid #000;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#ffffffad;">
                            <img src="{{ asset('front/images/logo/logo-1.png') }}" alt="Logo" style="width:45px;height:45px;object-fit:cover;">
                        </div>
                    </div>
                    <div style="position:absolute;bottom:12px;right:12px;color:#000;font-weight:600;font-size:12px;display:flex;gap:12px;align-items:center;">
                        <span>JAJAN EMAS</span>
                        <span>ANTAM</span>
                    </div>
                </div>
            </div>
            <div class="mt-24">
                <h4>Menu</h4>
                <div dir="ltr" class="mt-20 swiper tf-sw" data-preview="4" data-space="20">
                    <div class="swiper-wrapper">
                        @foreach(($menus ?? []) as $m)
                            <div class="swiper-slide">
                                <div class="category-job">
                                    <a href="{{ $m->path_url ?? '#' }}" class="box-img">
                                        @php $img = $m->image; @endphp
                                        <img src="{{ empty($img) ? asset('front/images/category/company.png') : (Str::startsWith($img, ['http://','https://','/']) ? $img : asset(ltrim($img, '/'))) }}" alt="img">
                                    </a>
                                    <a class="title" href="{{ $m->path_url ?? '#' }}">{{ $m->label }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- <input type="text" id="datepicker1" value="18 Februray, 2001" placeholder="Select date of birth" class="ip-datepicker form-control line-dark hasDatepicker"> -->
            <div class="mt-24">
                <div class="fl-title d-flex justify-content-between align-items-center">
                    <h4>All Product</h4>
                    <a href="{{ route('customer.product-dan-layanan') }}" class="button-1">See All</a>
                </div>
                <div class="mt-24 mb-50">
            <div class="list-app">
                @php $produkFiltered = ($produk ?? collect())->filter(function ($p) { return ($p->is_allow_po ?? false) || ($p->is_allow_ready ?? false); }); $produkCount = $produkFiltered->count(); @endphp
                @if($produkCount === 0)
                    <div class="text-center text-muted py-3">Produk belum tersedia.</div>
                @else
                    @foreach($produkFiltered as $p)
                        <div class="box-app">
                            <div class="info-box">
                                <a href="{{ ($p->is_allow_po ? route('customer.po.create', ['pid' => encrypt((string)$p->id)]) : ($p->is_allow_ready ? route('customer.ready.index') : '#')) }}" class="logo">
                                    @php $img = $p->image_produk; @endphp
                                    <img src="{{ empty($img) ? asset('front/images/golds/antam_1.jpg') : (Str::startsWith($img, ['http://','https://','/']) ? $img : asset(ltrim($img, '/'))) }}" alt="logo">
                                </a>
                                <div class="content">
                                    <div class="h7 text-dark">
                                        <a href="{{ ($p->is_allow_po ? route('customer.po.create', ['pid' => encrypt((string)$p->id)]) : ($p->is_allow_ready ? route('customer.ready.index') : '#')) }}">
                                            {{ $p->gramasi?->nama ?? 'Produk' }} {{ number_format((float) ($p->gramasi?->gramasi ?? 0), 3) }} gr <span style="font-family: monospace !important;"> (Harga : Rp {{ number_format((float) (($p->harga_hariini ?? 0) + ($p->harga_jasa ?? 0)), 0) }})</span>
                                        </a>
                                       
                                    </div>
                                    <div class="box-map-date">
                                        <div class="d-flex gap-4 align-items-center">
                                            <i class="icon icon-wallet-2 text-primary"></i>
                                            <span class="body-3 text-dark-4">Harga: Rp {{ number_format((float) ($p->harga_hariini ?? 0), 0) }}</span>
                                        </div>
                                        <div class="d-flex gap-4 align-items-center">
                                            <i class="icon icon-wallet-2 text-primary"></i>
                                            <span class="body-3 text-dark-4">Jasa: Rp {{ number_format((float) ($p->harga_jasa ?? 0), 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                @php $hasPo = (bool)($p->is_allow_po ?? false); $hasReady = (bool)($p->is_allow_ready ?? false); $colClass = ($hasPo && $hasReady) ? 'col-6' : 'col-12'; @endphp
                                <div class="row g-2">
                                    @if($hasPo)
                                        <div class="{{ $colClass }}">
                                            <a href="{{ route('customer.po.create', ['pid' => encrypt((string)$p->id)]) }}" class="btn-app button-1 w-100">Jastip Emas</a>
                                        </div>
                                    @endif
                                    @if($hasReady)
                                        <div class="{{ $colClass }}">
                                            <a href="{{ route('customer.ready.index') }}" class="btn-app button-1 view-app w-100">Emas Ready</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
             </div> 
            </div>
        </div>
    </div>
@include('front.customer.partials.menubar-footer', ['active' => 'dashboard'])>
    <!-- notification  -->
    <div class="offcanvas offcanvas-end full" id="notification">
        <div class="header fixed-top">
            <div class="left">
                <a href="javascript:void(0);" data-bs-dismiss="offcanvas" class="icon">
                    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <h3>Notification</h3>
        </div>
        <div class="overflow-auto app-content style-4">
            <div class="tf-container">
                <div class="list-noti">
                    <div class="box-noti">
                        <a href="job-detail" class="box-icon">
                            <img src="{{ asset('front/images/icon/google.png') }}" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail">Product Design</a> <span
                                        class="dot"></span><span class="body-6 text-dark-4">Google LLC</span></div>
                            </div>
                            <p class="desc body-4 text-dark-4">Congratulations, your application on Google has been
                                accepted <span class="dot away"></span><span class="body-6 text-dark-4">5 mins
                                    ago</span></p>
                        </div>
                        <div class="more">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.99984 3.25C10.5023 3.25 10.9165 3.66421 10.9165 4.16667C10.9165 4.66912 10.5023 5.08333 9.99984 5.08333C9.49738 5.08333 9.08317 4.66912 9.08317 4.16667C9.08317 3.66421 9.49738 3.25 9.99984 3.25Z"
                                    fill="#121927" stroke="#121927" stroke-width="1.5" />
                                <path
                                    d="M9.99984 14.9165C10.5023 14.9165 10.9165 15.3307 10.9165 15.8332C10.9165 16.3356 10.5023 16.7498 9.99984 16.7498C9.49738 16.7498 9.08317 16.3356 9.08317 15.8332C9.08317 15.3307 9.49738 14.9165 9.99984 14.9165Z"
                                    fill="#121927" stroke="#121927" stroke-width="1.5" />
                                <path
                                    d="M9.99984 9.0835C10.5023 9.0835 10.9165 9.49771 10.9165 10.0002C10.9165 10.5026 10.5023 10.9168 9.99984 10.9168C9.49738 10.9168 9.08317 10.5026 9.08317 10.0002C9.08317 9.49771 9.49738 9.0835 9.99984 9.0835Z"
                                    fill="#121927" stroke="#121927" stroke-width="1.5" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/nouislider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/rangle-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/init.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>

</body>

</html>