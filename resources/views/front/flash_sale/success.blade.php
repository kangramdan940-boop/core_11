<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <title>Transaksi Berhasil</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<section class="boarding-sec">
    <div class="tf-container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;background-color:#d1e7dd;">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" fill="#28a745" opacity="0.15"></circle>
                                    <path d="M20 7L9 18L4 13" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="title mb-2">Transaksi Berhasil</h3>
                        <p class="desc mb-4">Terima kasih! Permintaan Anda telah berhasil diproses. Silakan lanjut sesuai informasi yang diberikan.</p>
                        <div class="d-flex justify-content-center">
                            <a href="{{ url('/') }}" class="tf-btn primary d-block w-100" style="max-width:320px">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>