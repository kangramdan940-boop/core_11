
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
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}" />
    <title>Profile</title>
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
            <a href="{{ route('customer.dashboard') }}" class="icon">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
        <h3>Profile</h3>
    </div>
    <div class="app-content app-fullheight">
        <div class="tf-container">
            <div class="box-profile text-center mt-20">
                <div class="avatar">
                    <img src="{{ asset('front/images/avt/avt-1.jpg') }}" alt="avt">
                </div>
                <div class="content">
                    <h4 class="name">{{ $customer->full_name ?? optional(auth()->user())->name ?? '' }}</h4>
                    <p class="text-mail body-4">{{ $customer->email ?? optional(auth()->user())->email ?? '' }}</p>
                    <a href="#" class="button-1 text-primary-violet">{{ ($customer && $customer->is_active) ? 'Aktif' : 'Tidak Aktif' }}</a>
                </div>
            </div>
            <div class="mt-22 wrap-count">
                <div class="count-item">
                    <div class="box-count bg-rgba-violet">
                        <div class="h1 text-secondary-violet">{{ $orderStats['po'] ?? 0 }}</div>
                    </div>
                    <span class="text-dark body-2 fw-medium">PO</span>
                </div>
                <div class="count-item">
                    <div class="box-count bg-rgba-pink">
                        <div class="h1 text-secondary-pink">{{ $orderStats['ready'] ?? 0 }}</div>
                    </div>
                    <span class="text-dark body-2 fw-medium">Ready</span>
                </div>
                <div class="count-item">
                    <div class="box-count bg-rgba-green-2">
                        <div class="h1 text-secondary-green">{{ $orderStats['cicilan'] ?? 0 }}</div>
                    </div>
                    <span class="text-dark body-2 fw-medium">Cicilan</span>
                </div>
            </div>
            <form action="{{ route('customer.profile.update') }}" method="POST" class="mt-24 mb-100">
                @csrf
                @method('PUT')
                <div class="form-field">
                    <h6 class="label text-dark-4">Full Name</h6>
                    <fieldset class="mt-12">
                        <input type="text" disabled name="full_name" value="{{ old('full_name', $customer->full_name ?? '') }}" placeholder="Type your full name" class="form-control line-dark" readonly>
                    </fieldset>
                </div>
                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">WhatsApp</h6>
                    <fieldset class="mt-12">
                        <input type="text"disabled name="phone_wa" value="{{ old('phone_wa', $customer->phone_wa ?? '') }}" placeholder="Type your WhatsApp" class="form-control line-dark" readonly>
                    </fieldset>
                </div>
                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Email</h6>
                    <fieldset class="mt-12">
                        <span class="icon"></span>
                        <input type="email"disabled name="email" value="{{ old('email', $customer->email ?? '') }}" placeholder="Type your email" class="form-control line-dark" readonly>
                    </fieldset>
                </div>
                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Birth Date</h6>
                    <fieldset class="mt-12">
                        <span class="icon"></span>
                        <input type="date" name="birth_date" value="{{ old('birth_date', optional($customer->birth_date)->format('Y-m-d')) }}" class="form-control line-dark">
                    </fieldset>
                </div>
                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Alamat</h6>
                    <fieldset class="mt-12">
                        <span class="icon"></span>
                        <input type="text" name="address_line" value="{{ old('address_line', $customer->address_line ?? '') }}" placeholder="Tulis alamat lengkap" class="form-control line-dark">
                    </fieldset>
                </div>
                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Kota</h6>
                    <fieldset class="mt-12">
                        <span class="icon"></span>
                        <input type="text" name="kota" value="{{ old('kota', $customer->kota ?? '') }}" class="form-control line-dark">
                    </fieldset>
                </div>
                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Provinsi</h6>
                    <fieldset class="mt-12">
                        <span class="icon"></span>
                        <input type="text" name="provinsi" value="{{ old('provinsi', $customer->provinsi ?? '') }}" class="form-control line-dark">
                    </fieldset>
                </div>
                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Kode Pos</h6>
                    <fieldset class="mt-12">
                        <span class="icon"></span>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $customer->kode_pos ?? '') }}" class="form-control line-dark">
                    </fieldset>
                </div>
                <div class="mt-24">
                    <button type="submit" class="tf-btn primary">Update Profil</button>
                </div>
            </form>
        </div>
    </div>
@include('front.customer.partials.menubar-footer', ['active' => 'profile'])
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