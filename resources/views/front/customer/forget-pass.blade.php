<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}">
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="{{ route('customer.login') }}" class="icon back-btn">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    <h3>Lupa Password</h3>
</div>
<div class="app-content style-4 signIn-area">
    <div class="tf-container">
        <div class="logo-account mt-20">
            <img class="logo-white" src="{{ asset('front/images/logo/logo-light.png') }}" alt="">
        </div>
        <p class="mt-16 body-2 text-dark-4">Masukkan email Anda untuk instruksi reset password.</p>
        @if(session('message'))
            <div class="alert alert-primary mt-3">{{ session('message') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
        @endif
        <form class="mt-20" action="{{ route('customer.forgot.submit') }}" method="POST">
            @csrf
            <div class="form-field">
                <h6 class="label">Email</h6>
                <fieldset class="input-icon mt-12">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M14.1665 17.0834H5.83317C3.33317 17.0834 1.6665 15.8334 1.6665 12.9167V7.08341C1.6665 4.16675 3.33317 2.91675 5.83317 2.91675H14.1665C16.6665 2.91675 18.3332 4.16675 18.3332 7.08341V12.9167C18.3332 15.8334 16.6665 17.0834 14.1665 17.0834Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.1668 7.5L11.5585 9.58333C10.7002 10.2667 9.29183 10.2667 8.43349 9.58333L5.8335 7.5" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
                    </span>
                    <input type="email" name="email" placeholder="Alamat email" class="form-control" required>
                </fieldset>
            </div>
            <button class="mt-24 tf-btn primary" type="submit">Reset Password</button>
        </form>
    </div>
</div>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.min.js') }}"></script>
<script src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>