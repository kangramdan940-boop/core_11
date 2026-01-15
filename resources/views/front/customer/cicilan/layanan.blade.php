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
    <title>Detail Layanan Cicilan || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="{{ route('customer.cicilan.index') }}" class="icon back-btn">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>
    </div>
    <h3>Detail Layanan Cicilan</h3>
</div>

<div class="app-content style-3">
    <div class="tf-container">
        <div class="card shadow-sm mb-3">
            <div class="card-body p-0">
                <div class="p-3">
                    <div class="h6 mb-2">{{ $layanan->nama_layanan }}</div>
                    <div class="row g-3">
                        <div class="col-6"><div class="body-6 text-dark-4">Nomor Akad</div><div class="h7 text-dark">{{ $akad?->nomor_akad ?? '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Tanggal Akad</div><div class="h7 text-dark">{{ optional($akad?->tanggal_akad)->format('Y-m-d') ?? '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Tenor</div><div class="h7 text-dark">{{ $akad?->tenor_bulan ?? ((int)$layanan->tenor_min_bulan.'–'.(int)$layanan->tenor_max_bulan) }} bulan</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">DP</div><div class="h7 text-dark">{{ $akad && $akad->dp_amount !== null ? ('Rp '.number_format((float)$akad->dp_amount, 0)) : (number_format((float)$layanan->dp_min_persen, 2)."%–".number_format((float)$layanan->dp_max_persen, 2)."%") }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Cicilan/Bulan</div><div class="h7 text-dark">{{ $akad && $akad->cicilan_per_bulan !== null ? 'Rp '.number_format((float)$akad->cicilan_per_bulan, 0) : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Gramasi Total</div><div class="h7 text-dark">{{ $akad && $akad->gramasi_total !== null ? number_format((float)$akad->gramasi_total, 3).' g' : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Harga/Gram</div><div class="h7 text-dark">{{ $akad && $akad->harga_per_gram_fix !== null ? 'Rp '.number_format((float)$akad->harga_per_gram_fix, 0) : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Total Kontrak</div><div class="h7 text-dark">{{ $akad && $akad->harga_total_kontrak !== null ? 'Rp '.number_format((float)$akad->harga_total_kontrak, 0) : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Status</div><span class="badge {{ ($akad?->status ?? 'draft') === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ strtoupper($akad?->status ?? 'DRAFT') }}</span></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Penjual</div><div class="h7 text-dark">{{ $akad?->penjual_nama ?? optional($akad?->agen)->name ?? '-' }}</div></div>
                    </div>
                </div>

                <div class="p-3 border-top">
                    <div class="h7 text-dark mb-2">Informasi Layanan</div>
                    <div class="row g-3">
                        <div class="col-6"><div class="body-6 text-dark-4">Kode Layanan</div><div class="h7 text-dark">{{ $layanan->kode_layanan }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Status Layanan</div><span class="badge {{ $layanan->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $layanan->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Tenor Tersedia</div><div class="h7 text-dark">{{ (int)$layanan->tenor_min_bulan }}–{{ (int)$layanan->tenor_max_bulan }} bulan</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">DP (%)</div><div class="h7 text-dark">{{ number_format((float)$layanan->dp_min_persen, 2) }}%–{{ number_format((float)$layanan->dp_max_persen, 2) }}%</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Margin (%)</div><div class="h7 text-dark">{{ $layanan->margin_persen !== null ? number_format((float)$layanan->margin_persen, 2).'%' : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Biaya Admin</div><div class="h7 text-dark">{{ $layanan->biaya_admin !== null ? 'Rp '.number_format((float)$layanan->biaya_admin, 0) : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Denda Terlambat (%)</div><div class="h7 text-dark">{{ $layanan->denda_terlambat_persen !== null ? number_format((float)$layanan->denda_terlambat_persen, 2).'%' : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Denda Terlambat (Rp)</div><div class="h7 text-dark">{{ $layanan->denda_terlambat_fixed !== null ? 'Rp '.number_format((float)$layanan->denda_terlambat_fixed, 0) : '-' }}</div></div>
                        <div class="col-6"><div class="body-6 text-dark-4">Grace Period</div><div class="h7 text-dark">{{ $layanan->grace_period_hari !== null ? ((int)$layanan->grace_period_hari.' hari') : '-' }}</div></div>
                        <div class="col-6">
                            <div class="body-6 text-dark-4">Pengiriman</div>
                            @php $types = is_array($layanan->allowed_delivery_types) ? $layanan->allowed_delivery_types : []; @endphp
                            <div class="h7 text-dark">{{ empty($types) ? '-' : implode(', ', array_map('strtoupper', $types)) }}</div>
                        </div>
                        <div class="col-12"><div class="body-6 text-dark-4">Catatan</div><div class="text-dark">{{ $layanan->catatan ?? '-' }}</div></div>
                        <div class="col-12"><div class="body-6 text-dark-4">Syarat dan ketentuan </div><div class="text-dark">{{ $layanan->syarat_ketentuan ?? '-' }}</div></div>
                    </div>
                </div>

                <div class="p-3 border-top">
                    <div class="h7 text-dark mb-2">Ringkasan Kontrak</div>
                    <div class="body-6 text-dark-4">
                        - DP dibayar di awal sesuai rentang yang diizinkan.<br>
                        - Cicilan per bulan = (Total Kontrak − DP) ÷ Tenor.<br>
                        - Margin/biaya admin/denda mengikuti konfigurasi layanan.<br>
                        - Pengiriman emas sesuai opsi pengiriman yang tersedia.
                    </div>
                </div>

                @php $pdfUrl = null; if (isset($akad) && $akad->file_pdf_url) { $pdfUrl = Str::startsWith($akad->file_pdf_url, ['http://','https://']) ? $akad->file_pdf_url : asset($akad->file_pdf_url); } @endphp
                @if ($pdfUrl)
                <div class="p-3 border-top">
                    <div class="h7 text-dark mb-2">Akad Murabahah (PDF)</div>
                    <div class="ratio ratio-4x3">
                        <iframe src="{{ $pdfUrl }}#toolbar=1&navpanes=0&scrollbar=1" title="PDF Preview" style="width:100%;height:100%;" frameborder="0"></iframe>
                    </div>
                    <div class="mt-2">
                        <a class="btn-app button-1" href="{{ $pdfUrl }}" target="_blank" rel="noopener">Buka PDF</a>
                    </div>
                </div>
                @else
                <div class="p-3 border-top">
                    <div class="h7 text-dark mb-2">Akad Murabahah (PDF)</div>
                    <div class="body-6 text-dark-4">Belum ada dokumen PDF terlampir.</div>
                </div>
                @endif

                <div class="p-3 d-flex gap-2">
                    <button type="button" class="btn-app button-1" onclick="window.location.href='{{ route('customer.cicilan.index') }}'">Mulai Cicilan (pilih emas)</button>
                </div>
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