<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}" />
    <title>Pemesanan Emas</title>
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

    <section class="tf-section">
        <div class="tf-container">
            <div class="d-flex flex-column align-items-center justify-content-center" style="min-height:60vh">
                <div class="text-center" style="max-width:560px">
                    <div class="mb-3">
                        <i class="icon icon-lock" style="font-size:48px;color:#F59E0B"></i>
                    </div>
                    <h1 class="title mb-2">Pemesanan Emas Belum Bisa Dibuka</h1>
                    <p class="desc body-3 text-dark-4 mb-4">
                        Fitur pemesanan emas sedang dinonaktifkan sementara. Kami tengah menyiapkan pengalaman
                        pemesanan yang lebih aman dan nyaman. Terima kasih atas pengertiannya.
                    </p>
                    <div class="d-grid gap-2" style="grid-template-columns: 1fr 1fr;">
                        <a href="{{ url('/') }}" class="tf-btn primary d-block w-100">Kembali ke Beranda</a>
                        <a href="{{ route('mitra.login') }}" class="tf-btn primary d-block w-100">Login Mitra</a>
                    </div>
                    <div class="mt-3">
                        <small class="body-5 text-dark-4">Butuh bantuan? Hubungi kami via WhatsApp yang tersedia di situs.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>