<!DOCTYPE html>
<html lang="en">
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
    <title>Profil Mitra</title>
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
            <a href="{{ route('mitra.dashboard') }}" class="icon">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
        <h3>Profil Mitra</h3>
        <div class="right">
            <a href="#" class="icon logout-btn" onclick="event.preventDefault(); document.getElementById('mitraLogoutForm').submit();" title="Logout">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 3h5a1 1 0 011 1v9a1 1 0 01-1 1H6" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 8.5h6M5 6l-2.5 2.5L5 11" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <form id="mitraLogoutForm" action="{{ route('mitra.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="app-content app-fullheight">
        <div class="tf-container">
            <div class="box-profile text-center mt-20">
                <div class="avatar">
                    <img src="{{ asset('front/images/avt/avt-1.jpg') }}" alt="avt">
                </div>
                <div class="content">
                    <h4 class="name">{{ $mitra->nama_lengkap ?? optional(auth()->user())->name ?? '' }}</h4>
                    <p class="text-mail body-4">{{ $mitra->email ?? optional(auth()->user())->email ?? '' }}</p>
                    <a href="#" class="button-1 text-primary-violet">{{ ($mitra && $mitra->is_active) ? 'Aktif' : 'Tidak Aktif' }}</a>
                </div>
            </div>

            <div class="mt-22 wrap-count">
                <div class="count-item">
                    <div class="box-count bg-rgba-violet" style="min-height: 100px; display: flex; align-items: center; justify-content: center; text-align: center;">
                        <div class="h7 text-secondary-violet">Account </div>
                        <div class="small text-secondary-violet">{{ $mitra->kode_mitra ?? '-' }}</div>
                    </div>
                    <span class="text-dark body-2 fw-medium">ode Mitra</span>
                </div>
                <div class="count-item">
                    <div class="box-count bg-rgba-pink" style="min-height: 100px; display: flex; align-items: center; justify-content: center; text-align: center;">
                        <div class="h7 text-secondary-pink">Limit</div>
                        <div class="h7 text-secondary-pink">{{ number_format((float)($mitra->harian_limit_gram ?? 0), 3, ',', '.') }}</div>
                    </div>
                </div>
                <div class="count-item">
                    <div class="box-count bg-rgba-green-2" style="min-height: 100px; display: flex; align-items: center; justify-content: center; text-align: center;">
                        <div class="h7 text-secondary-green">Komisi</div>
                        <div class="h7 text-secondary-green">{{ number_format((float)($mitra->komisi_persen ?? 0), 2, ',', '.') }}%</div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mt-16">
                    <div class="fw-semibold mb-2">Terjadi kesalahan:</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success mt-16">{{ session('success') }}</div>
            @endif

            @php $canEdit = (bool)($mitra->is_edit ?? false); @endphp
            @unless($canEdit)
                <div class="alert alert-warning light alert-dismissible fade show mb-10">Profil tidak dapat diedit saat ini.</div>
            @endunless

            <form action="{{ route('mitra.profile.update') }}" method="POST" class="mt-24 mb-100">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <h6 class="label text-dark-4">Nama Lengkap</h6>
                    <fieldset class="mt-12">
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mitra->nama_lengkap ?? '') }}" placeholder="Tulis nama lengkap" class="form-control line-dark" {{ $canEdit ? '' : 'disabled' }}>
                    </fieldset>
                </div>

                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">WhatsApp</h6>
                    <fieldset class="mt-12">
                        <input type="text" name="phone_wa" value="{{ old('phone_wa', $mitra->phone_wa ?? '') }}" placeholder="Contoh: 0812xxxxxxx" class="form-control line-dark" {{ $canEdit ? '' : 'disabled' }}>
                    </fieldset>
                    <div class="small text-muted mt-8">Format akan dinormalisasi ke awalan 0 (contoh: +62xxx → 0xxx).</div>
                </div>

                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Platform</h6>
                    <fieldset class="mt-12">
                        <input type="text" name="platform" value="{{ old('platform', $mitra->platform ?? '') }}" placeholder="Contoh: Tokopedia, Shopee, IG" class="form-control line-dark" {{ $canEdit ? '' : 'disabled' }}>
                    </fieldset>
                </div>

                <div class="mt-20 form-field">
                    <h6 class="label text-dark-4">Nomor Rekening</h6>
                    <fieldset class="mt-12">
                        <input type="text" name="account_no" value="{{ old('account_no', $mitra->account_no ?? '') }}" placeholder="Nomor akun/platform" class="form-control line-dark" {{ $canEdit ? '' : 'disabled' }}>
                    </fieldset>
                </div>

                <div class="mt-24">
                    <button type="submit" class="tf-btn primary" {{ $canEdit ? '' : 'disabled' }}>Update Profil</button>
                </div>
            </form>
        </div>
    </div>

@include('front.mitra.partials.menubar-footer', ['active' => 'profil'])
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>