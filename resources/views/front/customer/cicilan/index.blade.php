<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css')}}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css')}}" />
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png')}}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png')}}" />
    <title>Cicilan Emas || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="{{ route('customer.dashboard') }}" class="icon back-btn">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>
    </div>
    <h3>Cicilan Emas</h3>
</div>
<div class="app-content style-3">
    <div class="tf-container">

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="p-2">
                @forelse ($cicilanEmas as $e)
                    <div class="box-app" style="margin-bottom:16px;">
                        <div class="info-box">
                            <a href="javascript:void(0);" class="logo">
                                <img src="{{ asset('front/images/golds/antam_2.jpg') }}" alt="logo">
                            </a>
                            <div class="content">
                                <div class="h7 text-dark">
                                    <a href="javascript:void(0);">{{ $e->layanan->nama_layanan }}</a>
                                    <span class="dot"></span>
                                    <span class="body-6 text-dark-4">Tenor: {{ (int)$e->layanan->tenor_min_bulan }}–{{ (int)$e->layanan->tenor_max_bulan }} bln</span>
                                </div>
                                <div class="box-map-date">
                                    <div class="d-flex gap-4 align-items-center">
                                        <i class="icon icon-wallet-2 text-primary"></i>
                                        <span class="body-3 text-dark-4">Kode: {{ $e->layanan->kode_layanan }}</span>
                                    </div>
                                    <div class="d-flex gap-4 align-items-center">
                                        <i class="icon icon-map text-secondary-yellow"></i>
                                        <span class="body-3 text-dark-4">DP: {{ number_format((float)$e->layanan->dp_min_persen, 2) }}%–{{ number_format((float)$e->layanan->dp_max_persen, 2) }}%</span>
                                    </div>
                                    @php
                                        $pdfUrl = optional($e->latestAkad)->file_pdf_url;
                                        if ($pdfUrl) {
                                            $pdfUrl = \Illuminate\Support\Str::startsWith($pdfUrl, ['http://','https://']) ? $pdfUrl : asset($pdfUrl);
                                        }
                                    @endphp
                                    <div class="d-flex gap-4 align-items-center">
                                        <span class="body-3 text-dark-4">Akad Murabahah: {{ $e->latestAkad ? 'Tersedia' : 'Belum ada' }}
                                            @if($e->latestAkad && $pdfUrl)
                                                • <a href="{{ $pdfUrl }}" target="_blank" rel="noopener">Lihat PDF</a>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-btn">
                            <a href="{{ route('customer.cicilan.layanan', $e->layanan) }}" class="btn-app button-1">Detail Kontrak</a>
                            <a href="{{ route('customer.cicilan.choose', ['record' => encrypt((string)$e->id)]) }}" class="btn-app button-1 view-app">Pilih</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">Belum ada layanan cicilan tersedia.</div>
                @endforelse
            </div>
            <div class="p-2"></div>
        </div>
    </div>

    <div class="card shadow-sm mt-2">
        <div class="card-body p-0">
            <div class="p-2">
                <h6 class="mb-2">Kontrak Cicilan Saya</h6>
                @forelse ($contracts as $c)
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
                                        @php
                                            $s = (string) $c->status;
                                            $cls = 'bg-info';
                                            switch (strtolower($s)) {
                                                case 'menunggu dp': $cls = 'bg-warning text-dark'; break;
                                                case 'active': $cls = 'bg-primary'; break;
                                                case 'pembayaran telat': $cls = 'bg-warning text-dark'; break;
                                                case 'sudah di bayar': $cls = 'bg-success'; break;
                                                case 'selesai': $cls = 'bg-success'; break;
                                                case 'canceled': $cls = 'bg-danger'; break;
                                                case 'cancelled': $cls = 'bg-danger'; break;
                                                case 'completed': $cls = 'bg-success'; break;
                                                case 'defaulted': $cls = 'bg-danger'; break;
                                            }
                                        @endphp
                                        <span class="badge {{ $cls }}" style="font-size:.75rem;">{{ strtoupper($s) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">Belum ada kontrak cicilan.</div>
                @endforelse
            </div>
            @if(method_exists($contracts, 'hasPages') && $contracts->hasPages())
                <div class="p-2">{{ $contracts->links() }}</div>
            @endif
        </div>
    </div>
    </div>
</div>
@include('front.customer.partials.menubar-footer', ['active' => 'produk'])
<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
</body>
</html>