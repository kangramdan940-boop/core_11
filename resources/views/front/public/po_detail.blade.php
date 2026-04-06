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
    <title>Detail PO (Publik) || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="javascript:void(0)" class="icon back-btn" onclick="history.back()">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>
    </div>
    <h3>Detail Pesanan</h3>
</div>

<div class="app-content style-3">
    <div class="tf-container">
        <div class="alert alert-warning light mt-3">
            Halaman ini bersifat publik. Jangan membagikan link ini ke pihak yang tidak berkepentingan.
        </div>

        @php
            $requested = collect($requestedKodePos ?? [])->filter(function ($v) {
                return trim((string) $v) !== '';
            })->values();

            $kodeChips = $requested->map(function ($v) {
                return '[' . trim((string) $v) . ']';
            })->implode(', ');

            $firstPo = ($pos ?? collect())->first();
        @endphp

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-2">Kode PO</h6>
                <div class="small text-muted">{{ $kodeChips !== '' ? $kodeChips : '-' }}</div>
                @if ($firstPo)
                    <div class="mt-2 small">
                        <div><strong>Nama Customer:</strong> {{ optional($firstPo->customer)->full_name ?? '-' }}</div>
                        <div><strong>WA Customer:</strong> {{ optional($firstPo->customer)->phone_wa ?? '-' }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-3">Daftar Pesanan</h6>

                @foreach (($pos ?? collect()) as $po)
                    @php
                        $s = (string) ($po->status ?? '');
                        $badge = 'text-bg-secondary';
                        if ($s === 'paid' || $s === 'completed') { $badge = 'text-bg-success'; }
                        elseif ($s === 'cancelled') { $badge = 'text-bg-danger'; }
                        elseif ($s === 'pending_payment') { $badge = 'text-bg-warning'; }
                        elseif ($s === 'processing') { $badge = 'text-bg-info'; }
                        elseif ($s === 'ready_at_agen' || $s === 'shipped') { $badge = 'text-bg-primary'; }

                        $statusText = strtoupper($s);
                        if ($s === 'shipped') {
                            $statusText = !empty($po->resi_number) ? 'PENGIRIMAN' : 'PENGEMASAN';
                        }

                        $gramasi = optional(optional($po->produk)->gramasi)->gramasi;
                    @endphp

                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-semibold">{{ $po->kode_po ?? '-' }}</div>
                                <div class="small text-muted">{{ optional($po->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="badge rounded-pill {{ $badge }}">{{ $statusText }}</span>
                            </div>
                        </div>

                        <div class="row g-2 mt-2">
                            <div class="col-6"><strong>Gramasi</strong><br>{{ $gramasi ? number_format((float)$gramasi, 3, ',', '.') . ' gram' : '-' }}</div>
                            <div class="col-6"><strong>Qty</strong><br>{{ (int)($po->qty ?? 0) }} pcs</div>
                            <div class="col-6"><strong>Total Gram</strong><br>{{ number_format((float)($po->total_gram ?? 0), 3, ',', '.') }} gram</div>
                            <div class="col-6"><strong>Nomor Resi</strong><br>{{ $po->resi_number ?? '-' }}</div>
                        </div>
                    </div>
                @endforeach

                @if (($pos ?? collect())->count() === 0)
                    <div class="text-muted">Data pesanan tidak ditemukan.</div>
                @endif
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/lazysize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>