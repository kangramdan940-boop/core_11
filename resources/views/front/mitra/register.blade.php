<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css') }}"/>
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}" />
    <title>Register Mitra</title>
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
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ url('/') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
        <h3>Register Mitra</h3>
    </div>
    <div class="app-content style-4 signIn-area">
        <div class="tf-container">
            <div class="logo-account mt-20">
                <img class="logo-white" src="{{ asset('front/images/logo/logo-light.png') }}" alt="">
            </div>
            <form method="post" action="{{ route('mitra.register.submit') }}">
                @csrf
                <div class="mt-32 form-field form-2">
                    <h6 class="label">Nama Mitra</h6>
                    <fieldset class="input-icon mt-12">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M10.1331 9.05841C10.0498 9.05008 9.9498 9.05008 9.85814 9.05841C7.8748 8.99175 6.2998 7.36675 6.2998 5.36675C6.2998 3.32508 7.9498 1.66675 9.9998 1.66675C12.0415 1.66675 13.6998 3.32508 13.6998 5.36675C13.6915 7.36675 12.1165 8.99175 10.1331 9.05841Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.9666 12.1333C3.94993 13.4833 3.94993 15.6833 5.9666 17.0249C8.25827 18.5583 12.0166 18.5583 14.3083 17.0249C16.3249 15.6749 16.3249 13.4749 14.3083 12.1333C12.0249 10.6083 8.2666 10.6083 5.9666 12.1333Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama perusahaan/mitra" class="form-control">
                        @error('name')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    </fieldset>
                </div>
                <div class="mt-32 form-field form-2">
                    <h6 class="label">No Telepon (WhatsApp)</h6>
                    <fieldset class="input-icon mt-12">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M10.1331 9.05841C10.0498 9.05008 9.9498 9.05008 9.85814 9.05841C7.8748 8.99175 6.2998 7.36675 6.2998 5.36675C6.2998 3.32508 7.9498 1.66675 9.9998 1.66675C12.0415 1.66675 13.6998 3.32508 13.6998 5.36675C13.6915 7.36675 12.1165 8.99175 10.1331 9.05841Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.9666 12.1333C3.94993 13.4833 3.94993 15.6833 5.9666 17.0249C8.25827 18.5583 12.0166 18.5583 14.3083 17.0249C16.3249 15.6749 16.3249 13.4749 14.3083 12.1333C12.0249 10.6083 8.2666 10.6083 5.9666 12.1333Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
                        </span>
                        <input type="text" name="phone_wa" value="{{ old('phone_wa') }}" placeholder="ex: 08xxxxxxxxxx" class="form-control">
                        @error('phone_wa')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    </fieldset>
                </div>
                <div class="mt-20 form-field form-2">
                    <h6 class="label">Email</h6>
                    <fieldset class="input-icon mt-12">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M14.1665 17.0834H5.83317C3.33317 17.0834 1.6665 15.8334 1.6665 12.9167V7.08341C1.6665 4.16675 3.33317 2.91675 5.83317 2.91675H14.1665C16.6665 2.91675 18.3332 4.16675 18.3332 7.08341V12.9167C18.3332 15.8334 16.6665 17.0834 14.1665 17.0834Z" stroke="#121927" stroke-width="1.25" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.1668 7.5L11.5585 9.58333C10.7002 10.2667 9.29183 10.2667 8.43349 9.58333L5.8335 7.5" stroke="#121927" stroke-width="1.25" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email mitra" class="form-control">
                        @error('email')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    </fieldset>
                </div>
                <div class="mt-20 form-field form-2">
                    <h6 class="label">Password</h6>
                    <fieldset class="mt-16 input-icon">
                        <div class="box-view-hide">
                            <span class="icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M5 8.33341V6.66675C5 3.90841 5.83333 1.66675 10 1.66675C14.1667 1.66675 15 3.90841 15 6.66675V8.33341" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.99984 15.4167C11.1504 15.4167 12.0832 14.4839 12.0832 13.3333C12.0832 12.1827 11.1504 11.25 9.99984 11.25C8.84924 11.25 7.9165 12.1827 7.9165 13.3333C7.9165 14.4839 8.84924 15.4167 9.99984 15.4167Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.1665 18.3333H5.83317C2.49984 18.3333 1.6665 17.4999 1.6665 14.1666V12.4999C1.6665 9.16659 2.49984 8.33325 5.83317 8.33325H14.1665C17.4998 8.33325 18.3332 9.16659 18.3332 12.4999V14.1666C18.3332 17.4999 17.4998 18.3333 14.1665 18.3333Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
                            </span>
                            <input type="password" name="password" placeholder="Password" class="form-control password-field" required>
                            @error('password')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                            <div class="show-pass">
                                <span class="icon-pass icon-view"></span>
                                <span class="icon-pass icon-hide"></span>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="mt-20 form-field form-2">
                    <h6 class="label">Confirm Password</h6>
                    <fieldset class="mt-16 input-icon">
                        <div class="box-view-hide">
                            <span class="icon"></span>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi password" class="form-control password-field2" required>
                            @error('password_confirmation')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                            <div class="show-pass2">
                                <span class="icon-pass icon-view"></span>
                                <span class="icon-pass icon-hide"></span>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <button class="mt-40 tf-btn primary">Register</button>
            </form>
            <p class="mt-35 text-center body-2 text-dark-4">Sudah punya akun mitra? <a href="{{ route('mitra.login') }}" class="text-primary button-1">Masuk</a></p>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>