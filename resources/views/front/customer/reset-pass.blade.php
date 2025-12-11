<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}">
    <style>
        .accent-gold { color: #d9b846; }
        .btn-gold { background-color: #d9b846; color: #121927; border: none; }
        .btn-gold:hover { opacity: .9; }
        .logo-account img { max-height: 48px; }
        .header h3 { font-family: Calibri, Arial, sans-serif; }
    </style>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
    </head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="{{ route('customer.login') }}" class="icon back-btn">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    <h3>Reset Password</h3>
    </div>
<div class="app-content style-4 signIn-area">
    <div class="tf-container">
        <div class="logo-account mt-20">
            <img class="logo-white" src="{{ asset('front/images/logo/logo-light.png') }}" alt="">
        </div>
        <p class="mt-16 body-2 text-dark-4">Masukkan password baru Anda untuk akun <span class="accent-gold">{{ $email ?? '' }}</span>.</p>

        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mt-2">{{ $errors->first() }}</div>
        @endif

        <form class="mt-20" action="{{ route('customer.reset-password.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-field">
                <h6 class="label">Password Baru</h6>
                <fieldset class="mt-16 input-icon">
                    <div class="box-view-hide">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.5">
                                <path d="M5 8.33341V6.66675C5 3.90841 5.83333 1.66675 10 1.66675C14.1667 1.66675 15 3.90841 15 6.66675V8.33341" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.99984 15.4167C11.1504 15.4167 12.0832 14.4839 12.0832 13.3333C12.0832 12.1827 11.1504 11.25 9.99984 11.25C8.84924 11.25 7.9165 12.1827 7.9165 13.3333C7.9165 14.4839 8.84924 15.4167 9.99984 15.4167Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14.1665 18.3333H5.83317C2.49984 18.3333 1.6665 17.4999 1.6665 14.1666V12.4999C1.6665 9.16659 2.49984 8.33325 5.83317 8.33325H14.1665C17.4998 8.33325 18.3332 9.16659 18.3332 12.4999V14.1666C18.3332 17.4999 17.4998 18.3333 14.1665 18.3333Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>                                
                        </span>
                        <input type="password" name="password" placeholder="Password baru" class="form-control password-field" required minlength="6">
                        <div class="show-pass">
                            <span class="icon-pass icon-view">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.1083 7.8916L7.8916 12.1083C7.34994 11.5666 7.0166 10.8249 7.0166 9.99993C7.0166 8.34993 8.34993 7.0166 9.99993 7.0166C10.8249 7.0166 11.5666 7.34994 12.1083 7.8916Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.8499 4.8084C13.3915 3.7084 11.7249 3.1084 9.99987 3.1084C7.0582 3.1084 4.31654 4.84173 2.4082 7.84173C1.6582 9.01673 1.6582 10.9917 2.4082 12.1667C3.06654 13.2001 3.8332 14.0917 4.66654 14.8084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.0166 16.2751C7.9666 16.6751 8.97493 16.8917 9.99993 16.8917C12.9416 16.8917 15.6833 15.1584 17.5916 12.1584C18.3416 10.9834 18.3416 9.0084 17.5916 7.8334C17.3166 7.40006 17.0166 6.99173 16.7083 6.6084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.9252 10.5833C12.7085 11.7583 11.7502 12.7166 10.5752 12.9333" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.8915 12.1084L1.6665 18.3334" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.3334 1.66675L12.1084 7.89175" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> 
                            </span>
                            <span class="icon-pass icon-hide">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.1083 7.8916L7.8916 12.1083C7.34994 11.5666 7.0166 10.8249 7.0166 9.99993C7.0166 8.34993 8.34993 7.0166 9.99993 7.0166C10.8249 7.0166 11.5666 7.34994 12.1083 7.8916Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.8499 4.8084C13.3915 3.7084 11.7249 3.1084 9.99987 3.1084C7.0582 3.1084 4.31654 4.84173 2.4082 7.84173C1.6582 9.01673 1.6582 10.9917 2.4082 12.1667C3.06654 13.2001 3.8332 14.0917 4.66654 14.8084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.0166 16.2751C7.9666 16.6751 8.97493 16.8917 9.99993 16.8917C12.9416 16.8917 15.6833 15.1584 17.5916 12.1584C18.3416 10.9834 18.3416 9.0084 17.5916 7.8334C17.3166 7.40006 17.0166 6.99173 16.7083 6.6084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.9252 10.5833C12.7085 11.7583 11.7502 12.7166 10.5752 12.9333" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.8915 12.1084L1.6665 18.3334" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.3334 1.66675L12.1084 7.89175" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>   
                            </span>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="form-field mt-16">
                <h6 class="label">Konfirmasi Password</h6>
                <fieldset class="mt-16 input-icon">
                    <div class="box-view-hide">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.5">
                                <path d="M5 8.33341V6.66675C5 3.90841 5.83333 1.66675 10 1.66675C14.1667 1.66675 15 3.90841 15 6.66675V8.33341" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.99984 15.4167C11.1504 15.4167 12.0832 14.4839 12.0832 13.3333C12.0832 12.1827 11.1504 11.25 9.99984 11.25C8.84924 11.25 7.9165 12.1827 7.9165 13.3333C7.9165 14.4839 8.84924 15.4167 9.99984 15.4167Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14.1665 18.3333H5.83317C2.49984 18.3333 1.6665 17.4999 1.6665 14.1666V12.4999C1.6665 9.16659 2.49984 8.33325 5.83317 8.33325H14.1665C17.4998 8.33325 18.3332 9.16659 18.3332 12.4999V14.1666C18.3332 17.4999 17.4998 18.3333 14.1665 18.3333Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>                                
                        </span>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi password" class="form-control password-field2" required minlength="6">
                        <div class="show-pass2">
                            <span class="icon-pass icon-view">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.1083 7.8916L7.8916 12.1083C7.34994 11.5666 7.0166 10.8249 7.0166 9.99993C7.0166 8.34993 8.34993 7.0166 9.99993 7.0166C10.8249 7.0166 11.5666 7.34994 12.1083 7.8916Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.8499 4.8084C13.3915 3.7084 11.7249 3.1084 9.99987 3.1084C7.0582 3.1084 4.31654 4.84173 2.4082 7.84173C1.6582 9.01673 1.6582 10.9917 2.4082 12.1667C3.06654 13.2001 3.8332 14.0917 4.66654 14.8084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.0166 16.2751C7.9666 16.6751 8.97493 16.8917 9.99993 16.8917C12.9416 16.8917 15.6833 15.1584 17.5916 12.1584C18.3416 10.9834 18.3416 9.0084 17.5916 7.8334C17.3166 7.40006 17.0166 6.99173 16.7083 6.6084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.9252 10.5833C12.7085 11.7583 11.7502 12.7166 10.5752 12.9333" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.8915 12.1084L1.6665 18.3334" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.3334 1.66675L12.1084 7.89175" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> 
                            </span>
                            <span class="icon-pass icon-hide">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.1083 7.8916L7.8916 12.1083C7.34994 11.5666 7.0166 10.8249 7.0166 9.99993C7.0166 8.34993 8.34993 7.0166 9.99993 7.0166C10.8249 7.0166 11.5666 7.34994 12.1083 7.8916Z" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.8499 4.8084C13.3915 3.7084 11.7249 3.1084 9.99987 3.1084C7.0582 3.1084 4.31654 4.84173 2.4082 7.84173C1.6582 9.01673 1.6582 10.9917 2.4082 12.1667C3.06654 13.2001 3.8332 14.0917 4.66654 14.8084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.0166 16.2751C7.9666 16.6751 8.97493 16.8917 9.99993 16.8917C12.9416 16.8917 15.6833 15.1584 17.5916 12.1584C18.3416 10.9834 18.3416 9.0084 17.5916 7.8334C17.3166 7.40006 17.0166 6.99173 16.7083 6.6084" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.9252 10.5833C12.7085 11.7583 11.7502 12.7166 10.5752 12.9333" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.8915 12.1084L1.6665 18.3334" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.3334 1.66675L12.1084 7.89175" stroke="#121927" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>   
                            </span>
                        </div>
                    </div>
                </fieldset>
            </div>
            <button class="mt-24 tf-btn btn-gold" type="submit">Simpan Password</button>
        </form>
    </div>
</div>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.min.js') }}"></script>
<script src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>
