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
    <title>Detail Kontrak Cicilan || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="{{ route('customer.cicilan.index') }}" class="icon back-btn">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>
    </div>
    <h3>Kontrak Cicilan</h3>
</div>
<div class="app-content style-3">
    <div class="tf-container">

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <div class="fw-semibold mb-2">Terjadi kesalahan:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-2">Ringkasan Kontrak</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode</strong><br>{{ $contract->kode_kontrak }}</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($contract->status) }}</div>
                <div class="col-md-3"><strong>Gramasi</strong><br>{{ $contract->gramasi }}</div>
                <div class="col-md-3"><strong>Total</strong><br>{{ number_format((float)$contract->harga_total_kontrak, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Tenor</strong><br>{{ $contract->tenor_bulan }} bulan</div>
                <div class="col-md-3"><strong>DP</strong><br>{{ number_format((float)$contract->dp_amount, 2, ',', '.') }} ({{ $contract->dp_persen }}%)</div>
                <div class="col-md-3"><strong>Cicilan/bln</strong><br>{{ number_format((float)$contract->cicilan_per_bulan, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Sisa Tagihan</strong><br>{{ number_format((float)$contract->sisa_tagihan, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>

    @if (strtolower($contract->status) === 'menunggu dp')
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-2">Status Kontrak</h6>
            <div class="alert alert-info mb-0">Kontrak sedang menunggu verifikasi DP oleh admin. Jadwal cicilan akan tersedia setelah DP diverifikasi.</div>
        </div>
    </div>
    @else
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-2">Pembayaran Cicilan</h6>
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Cicilan Ke</th>
                        <th>Jatuh Tempo</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th style="width:260px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $p)
                        <tr>
                            <td>{{ $p->cicilan_ke }}</td>
                            <td>{{ optional($p->due_date)->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ number_format((float)$p->amount_due, 2, ',', '.') }}</td>
                            <td>{{ strtoupper($p->status) }}</td>
                            <td>{{ optional($p->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>
                                @php
                                    $firstUnpaidId = optional($payments->first(function($x){ return $x->status !== 'paid'; }))->id;
                                    $hasPendingLog = \App\Models\TransPaymentLog::where('ref_type','cicilan_payment')
                                        ->where('ref_id',$p->id)
                                        ->where('status','pending')
                                        ->exists();
                                @endphp
                                @if ($p->id === $firstUnpaidId)
                                    @if ($hasPendingLog)
                                        <span class="badge bg-warning text-dark">PROCESS</span>
                                    @elseif ($p->status === 'pending')
                                        <form action="{{ route('customer.cicilan.confirm-payment', $p) }}" method="POST" enctype="multipart/form-data" class="row g-2">
                                            @csrf
                                            <div class="col-auto">
                                                <input type="number" step="0.01" min="0.01" name="nominal_transfer" class="form-control form-control-sm" placeholder="Nominal" required value="{{ old('nominal_transfer', (int) floor((float) $p->amount_due) + (($p->id % 900) + 100)) }}">
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" name="nama_pengirim" class="form-control form-control-sm" placeholder="Nama Pengirim" required>
                                            </div>
                                            <div class="col-auto">
                                                <input type="file" name="bukti_transfer" class="form-control form-control-sm" accept="image/*" required>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-sm btn-primary">Konfirmasi</button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Belum ada jadwal cicilan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@include('front.customer.partials.menubar-footer', ['active' => 'produk'])
<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
</body>
</html>