<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kontrak Cicilan - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Detail Kontrak Cicilan</h1>
        <a href="{{ route('customer.cicilan.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
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

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-2">Pembayaran Cicilan</h6>
            <div class="table-responsive">
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
                                                    <input type="number" step="0.01" min="0.01" name="nominal_transfer" class="form-control form-control-sm" placeholder="Nominal" required>
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
    </div>
</div>
</body>
</html>