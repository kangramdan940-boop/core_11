<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Request Withdrawal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
</head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="{{ route('mitra.dashboard') }}" class="icon back-btn">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
    <h3>Request Withdrawal</h3>
    <div class="right"></div>
</div>

<div class="app-content style-2">
    <div class="tf-container">
        <div class="card shadow-sm p-3 mt-24" style="margin-bottom: 70px;">
            @if(session('error'))
                <div class="alert alert-danger light alert-dismissible fade show mb-10">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-primary light alert-dismissible fade show mb-10">{{ session('success') }}</div>
            @endif

            <div class="row g-3 mb-3">
                <div class="col-6"><strong>Saldo Komisi</strong><br>Rp {{ number_format((float)($saldoKomisi ?? 0), 2, ',', '.') }}</div>
                <div class="col-6"><strong>Sedang Diproses</strong><br>Rp {{ number_format((float)($wdPendingAmount ?? 0), 2, ',', '.') }}</div>
                <div class="col-6"><strong>Sukses WD</strong><br>Rp {{ number_format((float)($wdCompletedAmount ?? 0), 2, ',', '.') }}</div>
                <div class="col-6"><strong>Tersedia untuk WD</strong><br>Rp {{ number_format((float)($availableAmount ?? 0), 2, ',', '.') }}</div>
                <div class="col-12"><strong>Status WD Terakhir</strong><br>
                    @if(isset($latestWithdrawal))
                        @php($st = $latestWithdrawal->status)
                        @php($badge = 'bg-secondary')
                        @if($st === 'completed')
                            @php($badge = 'bg-success')
                        @elseif($st === 'processing')
                            @php($badge = 'bg-warning text-dark')
                        @elseif($st === 'checking')
                            @php($badge = 'bg-primary')
                        @elseif($st === 'requested')
                            @php($badge = 'bg-info text-dark')
                        @elseif($st === 'canceled')
                            @php($badge = 'bg-secondary')
                        @endif
                        <span class="badge {{ $badge }}">{{ strtoupper($st) }}</span>
                    @else
                        <span class="badge bg-secondary">BELUM ADA</span>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm p-3 mt-16 mb-10">
                <h5 class="mb-2">Riwayat WD</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nominal (Rp)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals ?? [] as $w)
                                @php($st = $w->status)
                                @php($badge = 'bg-secondary')
                                @if($st === 'completed')
                                    @php($badge = 'bg-success')
                                @elseif($st === 'processing')
                                    @php($badge = 'bg-warning text-dark')
                                @elseif($st === 'checking')
                                    @php($badge = 'bg-primary')
                                @elseif($st === 'requested')
                                    @php($badge = 'bg-info text-dark')
                                @elseif($st === 'canceled')
                                    @php($badge = 'bg-secondary')
                                @endif
                                <tr>
                                    <td>{{ $w->id }}</td>
                                    <td>{{ optional($w->requested_at)->format('Y-m-d H:i') ?? optional($w->created_at)->format('Y-m-d H:i') }}</td>
                                    <td>{{ number_format((float)$w->amount, 2, ',', '.') }}</td>
                                    <td><span class="badge {{ $badge }}">{{ strtoupper($st) }}</span></td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <form method="POST" action="{{ route('mitra.withdrawals.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nominal WD (Rp)</label>
                    <input type="number" name="amount" class="form-control" step="1" min="1000" value="{{ old('amount') }}" required>
                    <div class="form-text">Minimal Rp 1.000. Tidak boleh melebihi saldo tersedia.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Akun Tujuan</label>
                    <input type="text" class="form-control" value="{{ $mitra->account_no ?? '-' }}" disabled>
                    <div class="form-text">Ubah akun tujuan di halaman Profil jika perlu.</div>
                </div>
                @if(($availableAmount ?? 0) > 1000)
                    <button type="submit" class="btn btn-primary w-100">Kirim Request WD</button>
                @endif
            </form>
        </div>
    </div>
</div>

@include('front.mitra.partials.menubar-footer', ['active' => 'dashboard'])

<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.min.js') }}"></script>
<script src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>