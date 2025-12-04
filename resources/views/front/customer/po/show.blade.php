<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail PO Emas - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan PO</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode PO</strong><br>{{ $po->kode_po }}</div>
                @php
                    $s = $po->status;
                    $badge = 'text-bg-secondary';
                    if ($s === 'paid' || $s === 'completed') { $badge = 'text-bg-success'; }
                    elseif ($s === 'cancelled') { $badge = 'text-bg-danger'; }
                    elseif ($s === 'pending_payment') { $badge = 'text-bg-warning'; }
                    elseif ($s === 'processing') { $badge = 'text-bg-info'; }
                    elseif ($s === 'ready_at_agen' || $s === 'shipped') { $badge = 'text-bg-primary'; }
                @endphp
                <div class="col-md-3"><strong>Status</strong><br><span class="badge rounded-pill {{ $badge }}">{{ strtoupper($s) }}</span></div>
                <div class="col-md-3"><strong>Harga/Gram</strong><br>{{ number_format((float)$po->harga_per_gram, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Total Gram</strong><br>{{ number_format((float)$po->total_gram, 3, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Total Amount</strong><br>{{ number_format((float)$po->total_amount, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Tipe Penerimaan</strong><br>{{ $po->delivery_type }}</div>
            </div>
        </div>
    </div>

    @if ($po->delivery_type === 'ship')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Data Pengiriman</h6>
                <div class="row g-3">
                    <div class="col-md-6"><strong>Nama</strong><br>{{ $po->shipping_name ?? '-' }}</div>
                    <div class="col-md-6"><strong>No. HP</strong><br>{{ $po->shipping_phone ?? '-' }}</div>
                    <div class="col-md-12"><strong>Alamat</strong><br>{{ $po->shipping_address ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kota</strong><br>{{ $po->shipping_city ?? '-' }}</div>
                    <div class="col-md-4"><strong>Provinsi</strong><br>{{ $po->shipping_province ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kode Pos</strong><br>{{ $po->shipping_postal_code ?? '-' }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Informasi Proses</h6>
            @if ($po->status === 'pending_payment')
                <p class="mb-2">
                    Status: Menunggu pembayaran dari Anda. Silakan lakukan pembayaran sejumlah
                    <strong>{{ number_format((float)$po->total_amount, 2, ',', '.') }} IDR</strong>.
                </p>
                <p class="text-muted mb-2">
                    Setelah dana diterima oleh agen, status akan diperbarui menjadi <strong>paid</strong> dan proses berlanjut ke <strong>processing</strong>.
                </p>
                <p class="mb-0">
                    Jika Anda sudah melakukan transfer, mohon tunggu konfirmasi dari agen. Anda dapat memantau perubahan status di halaman ini.
                </p>
            @elseif ($po->status === 'paid')
                <p class="mb-0">Pembayaran diterima. Menunggu agen memproses pembelian emas di brankas.</p>
            @elseif ($po->status === 'processing')
                <p class="mb-0">Sedang diproses oleh agen. Emas akan disiapkan.</p>
            @elseif ($po->status === 'ready_at_agen')
                <p class="mb-0">Emas sudah siap di agen. Menunggu pengiriman atau pengambilan sesuai pilihan Anda.</p>
            @elseif ($po->status === 'shipped')
                <p class="mb-0">Emas telah dikirim. Mohon tunggu sampai diterima.</p>
            @elseif ($po->status === 'completed')
                <p class="mb-0">Transaksi selesai. Terima kasih.</p>
            @elseif ($po->status === 'cancelled')
                <p class="mb-0">Transaksi dibatalkan. Jika ada pengembalian dana, akan diproses terpisah.</p>
            @endif
        </div>
    </div>
    

    @if ($po->status === 'pending_payment')
    @php $pendingManual = ($paymentLogs ?? collect())
        ->filter(function($pl){ return ($pl->payment_method === 'manual_transfer') && ($pl->status === 'pending'); })
        ->count(); @endphp
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Konfirmasi Pembayaran Manual</h6>
            <form action="{{ route('customer.po.confirm-payment', $po) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nominal Transfer (IDR)</label>
                        <input type="number" name="nominal_transfer" step="0.01" class="form-control" placeholder="contoh: 1500000" value="{{ old('nominal_transfer') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Pengirim</label>
                        <input type="text" name="nama_pengirim" class="form-control" placeholder="Nama pada rekening" value="{{ old('nama_pengirim') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bukti Transfer (gambar)</label>
                        <input type="file" name="bukti_transfer" accept="image/*" class="form-control">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">Kirim Konfirmasi</button>
                </div>
            </form>
            <p class="text-muted small mt-2 mb-0">Setelah dikirim, agen akan memverifikasi pembayaran Anda. Status akan berubah menjadi <strong>paid</strong> jika dana telah diterima.</p>
        </div>
    </div>
   
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Payment Logs</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Dibayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paymentLogs as $pl)
                            <tr>
                                <td>{{ $pl->kode_payment }}</td>
                                @php $ps = $pl->status; $pbadge = 'text-bg-secondary'; if ($ps === 'paid') { $pbadge = 'text-bg-success'; } elseif ($ps === 'pending') { $pbadge = 'text-bg-warning'; } elseif ($ps === 'failed') { $pbadge = 'text-bg-danger'; } @endphp
                                <td><span class="badge rounded-pill {{ $pbadge }}">{{ strtoupper($ps) }}</span></td>
                                <td>{{ number_format((float)$pl->amount, 2, ',', '.') }} {{ $pl->currency }}</td>
                                <td>{{ $pl->payment_method ?? '-' }}</td>
                                <td>{{ optional($pl->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">Belum ada payment log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Timeline</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $l)
                            <tr>
                                <td>{{ optional($l->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ strtoupper($l->status) }}</td>
                                <td>{{ $l->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">Belum ada log.</td>
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