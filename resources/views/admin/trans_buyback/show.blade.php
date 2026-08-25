@extends('layouts.admin.master')

@section('title', 'Detail Buyback - Admin')
@section('sub-title', 'Transaksi Buyback')
@section('breadcrumbExtra', 'Detail Transaksi Buyback')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.buyback.index'))

@section('content')
    @php
        $badgeMap = [
            'pending_review' => 'bg-warning text-dark',
            'priced'         => 'bg-info text-dark',
            'approved'       => 'bg-primary',
            'paid'           => 'bg-success',
            'completed'      => 'bg-success',
            'rejected'       => 'bg-danger',
            'cancelled'      => 'bg-secondary',
        ];

        // Nomor WA customer
        $waRaw = optional($trx->customer)->phone_wa;
        $waDigits = $waRaw ? preg_replace('/\D+/', '', $waRaw) : null;
        if ($waDigits && substr($waDigits, 0, 1) === '0') { $waDigits = '62' . substr($waDigits, 1); }
        $custName = optional($trx->customer)->full_name ?? '';
        $totalStr = 'Rp ' . number_format((float)$trx->total_amount, 0, ',', '.');
        $waMsg = "Halo Kak {$custName},\n\nTerkait pengajuan Buyback Emas Anda:\nKode: {$trx->kode_trans}\nEmas: {$trx->brand} {$trx->berat_gram} gram x {$trx->qty}\nStatus: " . strtoupper(str_replace('_',' ',$trx->status)) . "\nEstimasi/Nilai: {$totalStr}\n\nTim Jajan Emas";
        $waUrl = $waDigits ? ('https://wa.me/' . $waDigits . '?text=' . rawurlencode($waMsg)) : null;
    @endphp

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.trans.buyback.index') }}" class="btn btn-secondary">← Kembali</a>
        <div class="d-flex align-items-center gap-2">
            @if($waUrl)
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-success">
                    <i class="fa fa-whatsapp me-1"></i> Chat Customer ({{ $waDigits }})
                </a>
            @else
                <span class="text-muted small">No. WA customer tidak tersedia</span>
            @endif
            <span class="badge {{ $badgeMap[$trx->status] ?? 'bg-secondary' }} fs-6">{{ strtoupper(str_replace('_',' ',$trx->status)) }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3 fs-5"># Ringkasan Buyback</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Kode Trans</strong><div class="mt-1">{{ $trx->kode_trans }}</div></div>
                        <div class="col-md-6"><strong>Customer</strong><div class="mt-1">{{ optional($trx->customer)->full_name ?? '-' }}</div></div>
                        <div class="col-md-4"><strong>Brand</strong><div class="mt-1">{{ $trx->brand ?? '-' }}</div></div>
                        <div class="col-md-4"><strong>Berat</strong><div class="mt-1">{{ number_format((float)$trx->berat_gram, 3, ',', '.') }} g</div></div>
                        <div class="col-md-4"><strong>Qty</strong><div class="mt-1">{{ $trx->qty }} keping</div></div>
                        <div class="col-md-4"><strong>Kondisi</strong><div class="mt-1">{{ $trx->kondisi ?? '-' }}</div></div>
                        <div class="col-md-4"><strong>Sertifikat</strong><div class="mt-1">{{ $trx->ada_sertifikat ? 'Ada' : 'Tidak' }}</div></div>
                        <div class="col-md-4"><strong>Metode Serah</strong><div class="mt-1">{{ $trx->metode_serah === 'kirim' ? 'Kirim' : 'Datang ke lokasi' }}</div></div>
                        @if($trx->resi_pengiriman)
                            <div class="col-md-12"><strong>Resi</strong><div class="mt-1">{{ $trx->resi_pengiriman }}</div></div>
                        @endif
                        <div class="col-md-12"><strong>Catatan Customer</strong><div class="mt-1">{{ $trx->catatan ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3 fs-5"># Harga</h6>
                    <div class="row g-3">
                        <div class="col-md-4"><strong>Estimasi / unit</strong><div class="mt-1">{{ number_format((float)$trx->harga_buyback_estimasi, 0, ',', '.') }}</div></div>
                        <div class="col-md-4"><strong>Final / unit</strong><div class="mt-1">{{ is_null($trx->harga_buyback_final) ? '-' : number_format((float)$trx->harga_buyback_final, 0, ',', '.') }}</div></div>
                        <div class="col-md-4"><strong>Total Diterima</strong><div class="mt-1 fw-bold text-success">Rp {{ number_format((float)$trx->total_amount, 0, ',', '.') }}</div></div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3 fs-5"># Rekening Tujuan (Customer)</h6>
                    <div class="row g-3">
                        <div class="col-md-4"><strong>Bank</strong><div class="mt-1">{{ $trx->bank_nama ?? '-' }}</div></div>
                        <div class="col-md-4"><strong>No. Rekening</strong><div class="mt-1">{{ $trx->rekening_nomor ?? '-' }}</div></div>
                        <div class="col-md-4"><strong>Atas Nama</strong><div class="mt-1">{{ $trx->rekening_atas_nama ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            @if($trx->bukti_transfer_path)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3 fs-5"># Bukti Transfer</h6>
                    <img src="{{ asset($trx->bukti_transfer_path) }}" class="img-fluid rounded" style="max-height:320px" alt="Bukti Transfer">
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-5">
            {{-- Aksi admin sesuai status --}}
            @if(in_array($trx->status, ['pending_review','priced'], true))
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3 fs-5"># Verifikasi & Tetapkan Harga Final</h6>
                    <form action="{{ route('admin.trans.buyback.set-price', $trx->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Harga Buyback Final (per unit)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" min="0" name="harga_buyback_final" class="form-control" value="{{ old('harga_buyback_final', $trx->harga_buyback_final ?? $trx->harga_buyback_estimasi) }}" required>
                            </div>
                            <div class="form-text">Total = harga × {{ $trx->qty }} keping.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Admin (opsional)</label>
                            <textarea name="catatan_admin" class="form-control" rows="2">{{ old('catatan_admin', $trx->catatan_admin) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i> Tetapkan Harga</button>
                    </form>
                </div>
            </div>
            @endif

            @if($trx->status === 'approved')
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3 fs-5"># Transfer Dana ke Customer</h6>
                    <p class="small text-muted">Customer telah menyetujui harga <strong>Rp {{ number_format((float)$trx->total_amount, 0, ',', '.') }}</strong>. Unggah bukti transfer untuk menyelesaikan.</p>
                    <form action="{{ route('admin.trans.buyback.pay', $trx->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Bukti Transfer</label>
                            <input type="file" name="bukti_transfer" accept="image/*" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-upload me-1"></i> Simpan Bukti (Paid)</button>
                    </form>
                </div>
            </div>
            @endif

            @if(!in_array($trx->status, ['completed','rejected','cancelled'], true))
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3 fs-5"># Update Status</h6>
                    <form action="{{ route('admin.trans.buyback.update-status', $trx->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select name="status" class="form-select" required>
                                <option value="approved">APPROVED (customer setuju harga)</option>
                                <option value="completed">COMPLETED (selesai)</option>
                                <option value="rejected">REJECTED (tolak)</option>
                                <option value="cancelled">CANCELLED (batal)</option>
                            </select>
                            <div class="form-text">
                                Urutan alur: <strong>Pending Review</strong> → <strong>Priced</strong> (tetapkan harga) → <strong>Approved</strong> (customer/admin setujui) → <strong>Paid</strong> (transfer) → <strong>Completed</strong>.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Admin (opsional)</label>
                            <textarea name="catatan_admin" class="form-control" rows="2">{{ old('catatan_admin') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
            @endif

            @if($trx->catatan_admin)
                <div class="alert alert-warning"><strong>Catatan Admin:</strong> {{ $trx->catatan_admin }}</div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Log Status</h6>
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr><th>ID</th><th>Status</th><th>Deskripsi</th><th>Dibuat</th></tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ strtoupper($log->status) }}</td>
                            <td>{{ $log->description ?? '-' }}</td>
                            <td>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-3">Belum ada log status.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
