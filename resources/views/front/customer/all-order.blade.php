
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
    <link rel="shortcut icon" href="images/logo/168.png" />
    <link rel="apple-touch-icon-precomposed" href="images/logo/168.png" />
    <title>All Job</title>
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
            <a href="javascript:void(0);" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
        <h3>All Order</h3>
    </div>
    <div class="app-content style-4">
        <div class="tf-container">
            <div class="list-app mt-24 mb-100pc">
                @php $totalTrans = ($orders->count() + $readyOrders->count() + $contracts->count()); @endphp
                @if($totalTrans === 0)
                    <div class="text-center text-muted py-3">Tidak ada transaksi.</div>
                @else
                    @foreach($orders as $o)
                        <div class="box-app">
                            <div class="info-box mb-0">
                                <a href="{{ route('customer.po.show', encrypt($o->id)) }}" class="logo">
                                    <img src="{{ asset('front/images/golds/antam_1.jpg') }}" alt="logo">
                                </a>
                                <div class="content">
                                    <div class="box-top">
                                        <div class="info">
                                            <span class="body-6">Pre Order</span>
                                            <div class="h7 text-dark"><a href="{{ route('customer.po.show', encrypt($o->id)) }}">PO {{ $o->kode_po }} {{ number_format((float) $o->total_gram, 3) }} gr</a></div>
                                        </div>
                                        <div class="check-icon">
                                            @php $s = $o->status; $cls = 'bg-info'; if ($s === 'pending_payment') { $cls = 'bg-warning text-dark'; } elseif ($s === 'completed') { $cls = 'bg-success'; } elseif ($s === 'cancelled') { $cls = 'bg-danger'; } @endphp
                                            <span class="badge {{ $cls }}" style="font-size:.75rem;">{{ strtoupper($s) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($readyOrders as $r)
                        <div class="box-app">
                            <div class="info-box mb-0">
                                <a href="{{ route('customer.ready.show', $r) }}" class="logo">
                                    <img src="{{ asset('front/images/golds/antam_2.jpg') }}" alt="logo">
                                </a>
                                <div class="content">
                                    <div class="box-top">
                                        <div class="info">
                                            <span class="body-6">Jastip</span>
                                            <div class="h7 text-dark"><a href="{{ route('customer.ready.show', $r) }}">{{ $r->readyStock?->brand ?? 'Emas Ready' }} {{ number_format((float) ($r->readyStock?->gramasi ?? 0), 3) }} gr {{ $r->qty }}(pcs)</a></div>
                                        </div>
                                        <div class="check-icon">
                                            @php $s = $r->status; $cls = 'bg-info'; if ($s === 'pending_payment') { $cls = 'bg-warning text-dark'; } elseif ($s === 'completed') { $cls = 'bg-success'; } elseif ($s === 'cancelled') { $cls = 'bg-danger'; } @endphp
                                            <span class="badge {{ $cls }}" style="font-size:.75rem;">{{ strtoupper($s) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($contracts as $c)
                        <div class="box-app">
                            <div class="info-box mb-0">
                                <a href="{{ route('customer.cicilan.show', $c) }}" class="logo">
                                    <img src="{{ asset('front/images/golds/antam_5.jpg') }}" alt="logo">
                                </a>
                                <div class="content">
                                    <div class="box-top">
                                        <div class="info">
                                            <span class="body-6">Cicilan</span>
                                            <div class="h7 text-dark"><a href="{{ route('customer.cicilan.show', $c) }}">Kontrak {{ $c->kode_kontrak }} {{ number_format((float) $c->gramasi, 3) }} gr</a></div>
                                        </div>
                                        <div class="check-icon">
                                            @php $s = $c->status; $cls = 'bg-info'; if ($s === 'completed') { $cls = 'bg-success'; } elseif ($s === 'cancelled') { $cls = 'bg-danger'; } @endphp
                                            <span class="badge {{ $cls }}" style="font-size:.75rem;">{{ strtoupper($s) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @include('front.customer.partials.menubar-footer', ['active' => 'all-order', 'poHref' => 'pre-order-emas'])>

    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/nouislider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/rangle-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>

</body>

</html>