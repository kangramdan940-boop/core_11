<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="images/logo/168.png" />
    <link rel="apple-touch-icon-precomposed" href="images/logo/168.png" />
    <title>Product || Layanan Jajan Emas</title>
    <!-- Apply dark theme early to avoid white flash -->
    <script>
        if (localStorage.toggled === "dark-theme") {
            document.documentElement.classList.add('dark-theme');
        }
    </script>

</head>

<body>
    <!-- preloade -->
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
    <div class="header fixed-top">
        <div class="left">
            <a href="javascript:void(0);" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
        <h3>Product & Layanan</h3>
        <div class="right">
            <a href="#notification" data-bs-toggle="offcanvas" class="icon noti-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 6.43994V9.76994" stroke="#121927" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" />
                    <path
                        d="M12.0199 2C8.3399 2 5.3599 4.98 5.3599 8.66V10.76C5.3599 11.44 5.0799 12.46 4.7299 13.04L3.4599 15.16C2.6799 16.47 3.2199 17.93 4.6599 18.41C9.4399 20 14.6099 20 19.3899 18.41C20.7399 17.96 21.3199 16.38 20.5899 15.16L19.3199 13.04C18.9699 12.46 18.6899 11.43 18.6899 10.76V8.66C18.6799 5 15.6799 2 12.0199 2Z"
                        stroke="#121927" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                    <path
                        d="M15.3299 18.8198C15.3299 20.6498 13.8299 22.1498 11.9999 22.1498C11.0899 22.1498 10.2499 21.7698 9.64992 21.1698C9.04992 20.5698 8.66992 19.7298 8.66992 18.8198"
                        stroke="#121927" stroke-width="1.5" stroke-miterlimit="10" />
                </svg>
            </a>
        </div>
    </div>
    <div class="app-content">
        <div class="tf-container">
            <div class="list-app mt-24">
                @php $produkCount = ($produk->count()); @endphp
                @if($produkCount === 0)
                    <div class="text-center text-muted py-3">Produk belum tersedia.</div>
                @else
                    @foreach($produk as $p)
                        <div class="box-app">
                            <div class="info-box">
                                <a href="{{ ($p->is_allow_po ? route('customer.po.create', ['pid' => encrypt((string)$p->id)]) : ($p->is_allow_ready ? route('customer.ready.index') : '#')) }}" class="logo">
                                    @php $img = $p->image_produk; @endphp
                                    <img src="{{ empty($img) ? asset('front/images/golds/antam_1.jpg') : (Str::startsWith($img, ['http://','https://','/']) ? $img : asset(ltrim($img, '/'))) }}" alt="logo">
                                </a>
                                <div class="content">
                                    <div class="h7 text-dark">
                                        <a href="{{ ($p->is_allow_po ? route('customer.po.create', ['pid' => encrypt((string)$p->id)]) : ($p->is_allow_ready ? route('customer.ready.index') : '#')) }}">
                                            {{ $p->gramasi?->nama ?? 'Produk' }} {{ number_format((float) ($p->gramasi?->gramasi ?? 0), 3) }} gr
                                        </a>
                                    </div>
                                    <div class="box-map-date">
                                        <div class="d-flex gap-4 align-items-center">
                                            <i class="icon icon-wallet-2 text-primary"></i>
                                            <span class="body-3 text-dark-4">Jasa: Rp {{ number_format((float) ($p->harga_jasa ?? 0), 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-btn">
                                @if($p->is_allow_po)
                                    <a href="{{ route('customer.po.create', ['pid' => encrypt((string)$p->id)]) }}" class="btn-app button-1">Jastip Emas</a>
                                @endif
                                @if($p->is_allow_ready)
                                    <a href="{{ route('customer.ready.index') }}" class="btn-app button-1 view-app">Emas Ready</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div> 
@include('front.customer.partials.menubar-footer', ['active' => 'produk', 'poHref' => 'pre-order-emas'])>
    

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
            <div class="right">
                <a href="#" class="icon search-box">
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.1175 14.8234C11.8211 14.8234 14.8234 11.8211 14.8234 8.1175C14.8234 4.41395 11.8211 1.41162 8.1175 1.41162C4.41395 1.41162 1.41162 4.41395 1.41162 8.1175C1.41162 11.8211 4.41395 14.8234 8.1175 14.8234Z"
                            stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15.5294 15.5294L14.1177 14.1177" stroke="#121927" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="overflow-auto app-content style-4">
            <div class="tf-container">
                <div class="list-noti">
                    <div class="box-noti">
                        <a href="job-detail.html" class="box-icon">
                            <img src="images/icon/google.png" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail.html">Product Design</a> <span
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
                    <div class="box-noti">
                        <a href="job-detail.html" class="box-icon">
                            <img src="images/icon/slack.png" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail.html">Apps Design</a> <span
                                        class="dot"></span><span class="body-6 text-dark-4">Slack</span></div>
                            </div>
                            <p class="desc body-4 text-dark-4">New job avaolable on <strong>Slack</strong>, (position -
                                <strong>Mobile Apps Designer</strong> ) <span class="dot away"></span><span
                                    class="body-6 text-dark-4">30 mins ago</span>
                            </p>
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
                    <div class="box-noti">
                        <a href="job-detail.html" class="box-icon">
                            <img src="images/icon/zapier.png" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail.html">UI/UX Design</a> <span
                                        class="dot"></span><span class="body-6 text-dark-4">Zapier</span></div>
                            </div>
                            <p class="desc body-4 text-dark-4">New job avaolable on <strong>Zapier</strong>, (position -
                                <strong>Application Designer</strong> ) <span class="dot away"></span><span
                                    class="body-6 text-dark-4">10 hr ago</span>
                            </p>
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
                    <div class="box-noti">
                        <a href="job-detail.html" class="box-icon">
                            <img src="images/icon/google.png" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail.html">Product Design</a> <span
                                        class="dot"></span><span class="body-6 text-dark-4">Google LLC</span></div>
                            </div>
                            <p class="desc body-4 text-dark-4">A strong interview strategy can boost your chances of
                                success <span class="dot away"></span><span class="body-6 text-dark-4">18 hr ago</span>
                            </p>
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
                    <div class="box-noti">
                        <a href="job-detail.html" class="box-icon">
                            <img src="images/icon/treehouse.png" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail.html">UX Researcher</a> <span
                                        class="dot"></span><span class="body-6 text-dark-4">Treehouse</span></div>
                            </div>
                            <p class="desc body-4 text-dark-4">Congratulations, your application on Google has been
                                accepted<span class="dot away"></span><span class="body-6 text-dark-4">1 day ago</span>
                            </p>
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
                    <div class="box-noti">
                        <a href="job-detail.html" class="box-icon">
                            <img src="images/icon/microsoft.png" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail.html">Product Design</a> <span
                                        class="dot"></span><span class="body-6 text-dark-4">Microsoft</span></div>
                            </div>
                            <p class="desc body-4 text-dark-4">Congratulations, your application on Microsoft has been
                                accepted<span class="dot away"></span><span class="body-6 text-dark-4">1 week ago</span>
                            </p>
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
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/countto.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>



</body>

</html>