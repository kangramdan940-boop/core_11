@extends('layouts.admin.master-auth')

@section('title', 'Admin Login | JajanEmas')

@section('css')
    @include('layouts.admin.partials.head-css', ['auth' => 'layout-auth'])
@endsection

@section('content')
    <div class="account-pages">
        <img src="{{ asset('assets/images/auth/auth_bg.jpg') }}" alt="auth_bg" class="auth-bg light">
        <img src="{{ asset('assets/images/auth/auth_bg_dark.jpg') }}" alt="auth_bg_dark" class="auth-bg dark">
        <div class="container">
            <div class="justify-content-center row gy-0">
                <div class="col-lg-6 auth-banners">
                    <div class="bg-login card card-body m-0 h-100 border-0">
                        <img src="{{ asset('assets/images/auth/bg-img-2.png') }}" class="img-fluid auth-banner" alt="auth-banner">
                        <div class="auth-contain">
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="text-center text-white my-4 p-4">
                                            <h3 class="text-white">Masuk Portal Admin</h3>
                                            <p class="mt-3">Kelola aplikasi JajanEmas dengan aman dan nyaman.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="text-center text-white my-4 p-4">
                                            <h3 class="text-white">Keamanan Data</h3>
                                            <p class="mt-3">Pantau dan lindungi data Anda secara efektif.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="text-center text-white my-4 p-4">
                                            <h3 class="text-white">Manajemen Pengguna</h3>
                                            <p class="mt-3">Atur pengguna, peran, dan izin dengan mudah.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="auth-box card card-body m-0 h-100 border-0 justify-content-center">
                        <div class="mb-5 text-center">
                            <img src="{{ asset('assets/images/logo/logo-logotype.png') }}" alt="JajanEmas" style="height:35px" class="mb-5">
                            <h4 class="fw-normal">Selamat datang di <span class="fw-bold text-primary">JajanEmas</span></h4>
                            <p class="text-muted mb-0">Masukkan email dan kata sandi untuk masuk.</p>
                        </div>

                        @if (session('danger'))
                            <div class="alert alert-danger" role="alert">{{ session('danger') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">Terjadi kesalahan input. Periksa kembali.</div>
                        @endif

                        <form class="form-custom mt-3" method="POST" action="{{ route('admin.login.submit') }}">
                            @csrf
                            <div class="mb-5">
                                <label class="form-label" for="login-email">Email<span class="text-danger ms-1">*</span></label>
                                <input type="email" class="form-control" id="login-email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-5">
                                <label class="form-label" for="LoginPassword">Password<span class="text-danger ms-1">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="LoginPassword" class="form-control" name="password" placeholder="Masukkan password" data-visible="false" required>
                                    <a class="input-group-text bg-transparent toggle-password" href="javascript:;" data-target="password">
                                        <i class="ri-eye-off-line text-muted toggle-icon"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-sm-6">
                                    <div class="form-check form-check-sm d-flex align-items-center gap-2 mb-0">
                                        <input class="form-check-input" type="checkbox" value="remember-me" id="remember-me">
                                        <label class="form-check-label" for="remember-me">Ingat saya</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-2 w-100">
                                <span class="indicator-label">Masuk</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
@endsection
