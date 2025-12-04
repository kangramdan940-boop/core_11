<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi Emas Ready - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2">
            <div class="fw-bold mb-1">Terjadi kesalahan:</div>
            <ul class="mb-0 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan Transaksi</h6>
            <div class="row g-3">
                <div class="col-md-4"><strong>Kode Transaksi</strong><br>{{ $ready->kode_trans }}</div>
                <div class="col-md-4"><strong>Status</strong><br>{{ strtoupper($ready->status) }}</div>
                <div class="col-md-4"><strong>Total (IDR)</strong><br>{{ number_format((float)$ready->total_amount, 2, ',', '.') }}</div>
                <div class="col-md-4"><strong>Qty</strong><br>{{ $ready->qty }}</div>
                <div class="col-md-4"><strong>Harga Satuan</strong><br>{{ number_format((float)$ready->harga_jual_satuan, 2, ',', '.') }}</div>
                <div class="col-md-4"><strong>Item</strong><br>{{ optional($ready->readyStock)->kode_item ?? '-' }}</div>
            </div>
        </div>
    </div>

    @if ($ready->delivery_type === 'ship')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Alamat Pengiriman</h6>
                <div class="row g-3">
                    <div class="col-md-4"><strong>Nama</strong><br>{{ $ready->shipping_name ?? '-' }}</div>
                    <div class="col-md-4"><strong>HP</strong><br>{{ $ready->shipping_phone ?? '-' }}</div>
                    <div class="col-md-12"><strong>Alamat</strong><br>{{ $ready->shipping_address ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kota</strong><br>{{ $ready->shipping_city ?? '-' }}</div>
                    <div class="col-md-4"><strong>Provinsi</strong><br>{{ $ready->shipping_province ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kode Pos</strong><br>{{ $ready->shipping_postal_code ?? '-' }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Informasi Proses</h6>
            @if ($ready->status === 'pending_payment')
                <p class="mb-2">
                    Status: Menunggu pembayaran sejumlah
                    <strong>{{ number_format((float)$ready->total_amount, 2, ',', '.') }} IDR</strong>.
                </p>
                <p class="text-muted mb-2">Setelah dana diterima oleh agen, status akan berubah menjadi <strong>paid</strong> dan dilanjutkan ke pengiriman atau pengambilan.</p>
            @elseif ($ready->status === 'paid')
                <p class="mb-0">Pembayaran diterima. Menunggu proses pengiriman/pengambilan.</p>
            @elseif ($ready->status === 'shipped')
                <p class="mb-0">Barang dikirim. Mohon tunggu sampai diterima.</p>
            @elseif ($ready->status === 'completed')
                <p class="mb-0">Transaksi selesai. Terima kasih.</p>
            @elseif ($ready->status === 'cancelled')
                <p class="mb-0">Transaksi dibatalkan. Jika ada pengembalian dana, akan diproses terpisah.</p>
            @endif
        </div>
    </div>

    @php
        $pendingManual = ($paymentLogs ?? collect())
            ->filter(fn($pl) => ($pl->payment_method === 'manual_transfer') && ($pl->status === 'pending'))
            ->count();
    @endphp

    @if ($ready->status === 'pending_payment' && $pendingManual === 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Konfirmasi Pembayaran Manual</h6>
                <form action="{{ route('customer.ready.confirm-payment', $ready) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nominal Transfer (IDR)</label>
                            <input type="number" name="nominal_transfer" step="0.01" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Pengirim</label>
                            <input type="text" name="nama_pengirim" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bukti Transfer</label>
                            <input type="file" name="bukti_transfer" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm">Kirim Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if(($paymentLogs ?? collect())->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Log Pembayaran</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentLogs as $pl)
                                <tr>
                                    <td>{{ $pl->kode_payment }}</td>
                                    <td>{{ $pl->payment_method }}</td>
                                    <td>{{ number_format((float)$pl->amount, 2, ',', '.') }}</td>
                                    <td>{{ strtoupper($pl->status) }}</td>
                                    <td>{{ optional($pl->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(($logs ?? collect())->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Log Status</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th>Deskripsi</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ strtoupper($log->status) }}</td>
                                    <td>{{ $log->description ?? '-' }}</td>
                                    <td>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>