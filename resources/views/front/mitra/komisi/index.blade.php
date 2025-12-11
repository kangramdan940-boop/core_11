<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Komisi Mitra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}" />
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
        <h3>Daftar Komisi</h3>
    </div>

    <div class="app-content style-3">
        <div class="tf-container">
            @php
                $komisiItems = ($komisiList ?? collect());
                $assignItems = ($assignments ?? collect());
            @endphp
            @if ($komisiItems->count() === 0 && $assignItems->count() === 0)
                <div class="alert alert-secondary light mt-16">Belum ada komisi yang tercatat untuk akun mitra ini.</div>
            @else
                @if ($komisiItems->count() > 0)
                    <h5 class="mt-16 mb-8">Komisi Aktif</h5>
                    @foreach ($komisiItems as $o)
                        @php
                            $isActive = (bool)($o->is_active ?? false);
                            $cls = $isActive ? 'bg-success' : 'bg-danger';
                            $periode = '';
                            if ($o->berlaku_mulai || $o->berlaku_sampai) {
                                $mulai = optional($o->berlaku_mulai)->format('Y-m-d') ?? '-';
                                $sampai = optional($o->berlaku_sampai)->format('Y-m-d') ?? '-';
                                $periode = $mulai . ' s/d ' . $sampai;
                            } else {
                                $periode = 'Berlaku terus';
                            }
                        @endphp
                        <div class="box-app">
                            <div class="info-box mb-0">
                                <a href="javascript:void(0);" class="logo">
                                    <img src="{{ asset('front/images/golds/antam_1.jpg') }}" alt="logo">
                                </a>
                                <div class="content">
                                    <div class="box-top">
                                        <div class="info">
                                            <span class="body-6">Komisi</span>
                                            <div class="h7 text-dark">
                                                <a href="javascript:void(0);">
                                                    {{ strtoupper($o->tipe_transaksi ?? 'PO') }} — {{ number_format((float)($o->komisi_persen ?? 0), 2, ',', '.') }}%
                                                </a>
                                            </div>
                                            <div class="body-6 text-dark-4">
                                                Periode: {{ $periode }}
                                            </div>
                                        </div>
                                        <div class="check-icon">
                                            <span class="badge {{ $cls }}" style="font-size:.75rem;">{{ $isActive ? 'ACTIVE' : 'NONACTIVE' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if ($assignItems->count() > 0)
                    <h5 class="mt-24 mb-8">Alokasi Komisi Saya</h5>
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode PO</th>
                                <th>Gram</th>
                                <th>% Komisi</th>
                                <th>Nominal (IDR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignItems as $a)
                                <tr>
                                    <td>{{ optional($a->tanggal_komisi)->format('Y-m-d') ?? '-' }}</td>
                                    <td>{{ optional($a->po)->kode_po ?? '-' }}</td>
                                    <td>{{ number_format((float)$a->jumlah_gram, 3, ',', '.') }}</td>
                                    <td>{{ number_format((float)$a->komisi_persen, 2, ',', '.') }}</td>
                                    <td>{{ number_format((float)$a->komisi_amount, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        </div>
    </div>

@include('front.mitra.partials.menubar-footer', ['active' => 'komisi'])
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>