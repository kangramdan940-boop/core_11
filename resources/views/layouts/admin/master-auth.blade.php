{{-- master_auth.blade.php  file  --}}
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <title>@yield('title', ' Jajan Emas Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Jajan Emas Admin">
    <meta name="keywords" content="">
    <meta content="Jajan Emas" name="author">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}">

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Jajan Emas Admin">
    <meta property="og:description"
        content="Jajan Emas Admin">
    <meta property="og:url" content="https://jajanimas.com/admin">
    <meta property="og:site_name" content="Jajan Emas">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        const AUTH_LAYOUT = true;
    </script>
    @yield('css')
</head>

<body>
    @include('layouts.admin.partials.header')
    @include('layouts.admin.partials.sidebar')
    @include('layouts.admin.partials.preloader')

    <!-- end page title -->

    @yield('content')

    @include('layouts.admin.partials.vendor-scripts')

    @yield('js')

</body>

</html>
