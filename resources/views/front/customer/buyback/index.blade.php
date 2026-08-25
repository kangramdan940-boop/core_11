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
    <title>Buyback Saya || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
    <style>
        .bb-wrap { padding-top: 16px; padding-bottom: 90px; }

        /* Tombol ajukan */
        .bb-cta {
            display:flex; align-items:center; justify-content:center; gap:8px;
            width:100%; padding:14px 16px; border-radius:12px;
            background:#1a7f37; color:#fff; font-size:14px; font-weight:600;
            text-decoration:none; box-shadow:0 2px 8px rgba(26,127,55,.25);
            transition:background .2s, box-shadow .2s;
        }
        .bb-cta:hover { background:#166b2e; color:#fff; box-shadow:0 4px 12px rgba(26,127,55,.32); }

        .bb-section-title {
            font-size:12px; font-weight:700; color:#9aa0a6;
            text-transform:uppercase; letter-spacing:.5px;
            margin:20px 2px 10px;
        }

        /* Kartu item */
        .bb-card {
            display:flex; align-items:center; gap:12px;
            background:#fff; border:1px solid #f0f0f0; border-radius:14px;
            box-shadow:0 1px 6px rgba(0,0,0,.05);
            padding:14px; margin-bottom:10px;
            text-decoration:none; color:inherit; transition:box-shadow .2s, transform .05s;
        }
        .bb-card:hover { box-shadow:0 4px 14px rgba(0,0,0,.1); }
        .bb-card:active { transform:scale(.995); }

        .bb-icon {
            width:46px; height:46px; border-radius:12px; flex-shrink:0;
            background:#eef7f0; display:flex; align-items:center; justify-content:center;
        }

        .bb-body { flex:1; min-width:0; }
        .bb-title { font-size:14px; font-weight:700; color:#1f2430; line-height:1.3; }
        .bb-code  { font-size:11px; color:#9aa0a6; margin-top:3px; font-family:monospace; letter-spacing:-.2px; }
        .bb-date  { font-size:11px; color:#b0b4b9; margin-top:2px; }

        .bb-right { flex-shrink:0; text-align:right; display:flex; flex-direction:column; align-items:flex-end; gap:6px; }
        .bb-amount { font-size:14px; font-weight:800; color:#1a7f37; white-space:nowrap; }

        .bb-badge {
            display:inline-block; font-size:10px; font-weight:700;
            padding:4px 10px; border-radius:20px; white-space:nowrap; line-height:1.2;
        }
        .bb-badge-pending_review { background:#fff3cd; color:#856404; }
        .bb-badge-priced         { background:#d1ecf1; color:#0c5460; }
        .bb-badge-approved       { background:#cce5ff; color:#004085; }
        .bb-badge-paid           { background:#d4edda; color:#155724; }
        .bb-badge-completed      { background:#c3e6cb; color:#0b5226; }
        .bb-badge-rejected       { background:#f8d7da; color:#721c24; }
        .bb-badge-cancelled      { background:#e2e3e5; color:#383d41; }

        /* Empty state */
        .bb-empty { text-align:center; color:#aeb2b7; padding:50px 20px; }
        .bb-empty-icon {
            width:64px; height:64px; border-radius:50%; background:#f2f5f3;
            display:flex; align-items:center; justify-content:center; margin:0 auto 14px;
        }
        .bb-empty p { font-size:13px; margin:0; }

        .bb-alert { border-radius:12px; font-size:13px; }
    </style>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.dashboard') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Buyback Saya</h3>
    </div>

    <div class="app-content style-3">
        <div class="tf-container">
            <div class="bb-wrap">

                @if(session('success'))
                    <div class="alert alert-success bb-alert py-2 mb-3">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger bb-alert py-2 mb-3">{{ session('error') }}</div>
                @endif

                <a href="{{ route('customer.buyback.create') }}" class="bb-cta">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Ajukan Buyback Baru
                </a>

                @php
                    $labelMap = [
                        'pending_review' => 'Menunggu Verifikasi',
                        'priced'         => 'Menunggu Persetujuan',
                        'approved'       => 'Disetujui',
                        'paid'           => 'Dana Ditransfer',
                        'completed'      => 'Selesai',
                        'rejected'       => 'Ditolak',
                        'cancelled'      => 'Dibatalkan',
                    ];
                @endphp

                @if($items->count() > 0)
                    <div class="bb-section-title">Riwayat Pengajuan</div>

                    @foreach($items as $it)
                        <a href="{{ route('customer.buyback.show', ['buyback' => encrypt((string)$it->id)]) }}" class="bb-card">
                            <div class="bb-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a7f37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                            </div>
                            <div class="bb-body">
                                <div class="bb-title">{{ $it->brand ?? 'Emas' }} &middot; {{ rtrim(rtrim(number_format((float)$it->berat_gram, 3, ',', '.'), '0'), ',') }} g &times; {{ $it->qty }}</div>
                                <div class="bb-code">{{ $it->kode_trans }}</div>
                                <div class="bb-date">{{ optional($it->created_at)->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="bb-right">
                                <span class="bb-badge bb-badge-{{ $it->status }}">{{ $labelMap[$it->status] ?? strtoupper($it->status) }}</span>
                                <span class="bb-amount">Rp {{ number_format((float)$it->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="bb-empty">
                        <div class="bb-empty-icon">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#c3c8cc" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                        </div>
                        <p>Belum ada pengajuan buyback.</p>
                        <p class="mt-1">Klik tombol di atas untuk menjual emas Anda.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @include('front.customer.partials.menubar-footer', ['active' => 'all-order'])
    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
</body>
</html>
