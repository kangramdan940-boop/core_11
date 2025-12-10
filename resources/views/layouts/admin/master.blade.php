<!DOCTYPE html>
<html lang="en" class="h-100" data-main-layout="vertical">

<head>
    <meta charset="utf-8">
    <title>@yield('title', ' Herozi - The Worlds Best Selling Bootstrap Admin & Dashboard Template by SRBThemes')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Herozi is the top-selling Bootstrap 5 admin dashboard template. With Dark Mode, multi-demo options, RTL support, and lifetime updates, it's perfect for web developers.">
    <meta name="keywords"
        content="Herozi bootstrap dashboard, bootstrap, bootstrap 5, html dashboard, web dashboard, admin themes, web design, figma, web development, fullcalendar, datatables, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dark mode, bootstrap button, frontend dashboard, responsive bootstrap theme">
    <meta content="SRBThemes" name="author">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}">

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Herozi - The World's Best Selling Bootstrap Admin Template by SRBThemes">
    <meta property="og:description"
        content="Herozi is the top-selling Bootstrap 5 admin dashboard template. With Dark Mode, multi-demo options, RTL support, and lifetime updates, it's perfect for web developers.">
    <meta property="og:url" content="https://themeforest.net/user/srbthemes/portfolio">
    <meta property="og:site_name" content="Herozi by SRBThemes">

    @yield('css')
    @include('layouts.admin.partials.head-css')
</head>

<body>

    @include('layouts.admin.partials.header')
    @include('layouts.admin.partials.sidebar')
    @include('layouts.admin.partials.preloader')


    <main class="app-wrapper">
        <div class="app-container">

            @include('layouts.admin.partials.breadcrumb')

            @if(request()->segment(1) === 'admin')
                @include('layouts.admin.partials.alert')
            @endif

            <!-- end page title -->

            @yield('content')
            @include('layouts.admin.partials.bottom-wrapper')
            @include('layouts.admin.partials.vendor-scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var e = document.getElementById('preloader');
                    if (e) {
                        setTimeout(function(){ e.classList.add('hidden'); }, 200);
                    }
                    var fsBtn = document.getElementById('fullscreen-button');
                    if (fsBtn) {
                        var togglePressed = function(active){
                            fsBtn.classList.toggle('active', active);
                            fsBtn.setAttribute('aria-pressed', active ? 'true' : 'false');
                        };
                        fsBtn.addEventListener('click', function(){
                            var docEl = document.documentElement;
                            var isFs = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
                            if (isFs) {
                                (document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen || document.msExitFullscreen).call(document);
                                togglePressed(false);
                            } else {
                                (docEl.requestFullscreen || docEl.webkitRequestFullscreen || docEl.mozRequestFullScreen || docEl.msRequestFullscreen).call(docEl);
                                togglePressed(true);
                            }
                        });
                        document.addEventListener('fullscreenchange', function(){
                            var isFs = !!document.fullscreenElement;
                            togglePressed(isFs);
                        });
                        document.addEventListener('webkitfullscreenchange', function(){
                            var isFs = !!document.webkitFullscreenElement;
                            togglePressed(isFs);
                        });
                        document.addEventListener('mozfullscreenchange', function(){
                            var isFs = !!document.mozFullScreenElement;
                            togglePressed(isFs);
                        });
                        document.addEventListener('MSFullscreenChange', function(){
                            var isFs = !!document.msFullscreenElement;
                            togglePressed(isFs);
                        });
                    }
                });
            </script>
            @yield('js')

</body>

</html>
