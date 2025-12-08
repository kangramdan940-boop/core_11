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
     <title>Onboarding</title>
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
 
     <section class="boarding-sec">
         <div class="tf-container">
             <div dir="ltr" class="swiper tf-swiper-2" data-space-between="24" data-preview="1" data-tablet="1" data-desktop="1">
                 <div class="swiper-wrapper">
                     @forelse($slides as $slide)
                     <div class="swiper-slide">
                         <div class="boarding-img">
                             <div class="img">
                                 <img src="{{ $slide['image_src'] }}" alt="">
                             </div>
                         </div>
                         <div class="content-boarding text-center">
                             <h1 class="title">{!! nl2br(e($slide['title'])) !!}</h1>
                             @if(!empty($slide['description']))
                             <p class="desc">{!! nl2br(e($slide['description'])) !!}</p>
                             @endif
                         </div>
                     </div>
                     @empty
                     <div class="swiper-slide">
                         <div class="content-boarding text-center">
                             <h1 class="title">Selamat datang</h1>
                             <p class="desc">Tidak ada slider tersedia.</p>
                         </div>
                     </div>
                     @endforelse
                 </div>
                 <div class="swiper-pagination line-tes"></div>
             </div>
         </div>
         <div class="fixed-button group-btn-boarding">
            <div class="row g-2">
                <div class="col-6 text-center">
                    <a href="{{ route('order.unavailable') }}" class="tf-btn primary d-block w-100 mb-0">Beli Emas</a>
                </div>
                <div class="col-6 text-center">
                    <a href="{{ route('mitra.login') }}" class="tf-btn primary d-block w-100 mb-0">Login Mitra</a>
                </div>
            </div>
        </div>
     </section>
 
     <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
     <script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
     <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js') }}"></script>
     <script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
     <script type="text/javascript" src="{{ asset('front/js/carousel.js') }}"></script>
     <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>
 
 </body>
 
 </html>
