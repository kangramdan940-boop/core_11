<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Stok Emas Ready - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('customer.ready.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        <a href="{{ route('customer.ready.buy', $stock) }}" class="btn btn-success btn-sm">Beli</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h6 mb-3">Detail Stok Emas Ready</h1>

            <div class="row g-3">
                <div class="col-md-4"><strong>Kode Item</strong><br>{{ $stock->kode_item }}</div>
                <div class="col-md-4"><strong>Brand</strong><br>{{ strtoupper($stock->brand) }}</div>
                <div class="col-md-4"><strong>Gramasi</strong><br>{{ number_format((float)$stock->gramasi, 3, ',', '.') }} g</div>
                <div class="col-md-4"><strong>Kondisi</strong><br>{{ strtoupper($stock->kondisi_barang) }}</div>
                <div class="col-md-4"><strong>Status</strong><br>{{ strtoupper($stock->status) }}</div>
                <div class="col-md-4"><strong>Harga Jual</strong><br>{{ number_format((float)($stock->harga_jual_fix ?? $stock->harga_jual_minimal ?? 0), 2, ',', '.') }} IDR</div>
                <div class="col-md-12"><strong>Lokasi Simpan</strong><br>{{ $stock->lokasi_simpan ?? '-' }}</div>
                <div class="col-md-12"><strong>Catatan</strong><br>{{ $stock->catatan ?? '-' }}</div>
            </div>

            <hr>
            <div class="mb-2 text-muted">
                Jika Anda membeli dengan metode pengiriman, formulir akan otomatis terisi dengan data profil Anda:
                {{ $customer->full_name ?? '-' }}, {{ $customer->phone_wa ?? '-' }},
                {{ $customer->address_line ?? '-' }}, {{ $customer->kota ?? '-' }}, {{ $customer->provinsi ?? '-' }}, {{ $customer->kode_pos ?? '-' }}.
            </div>
            <a href="{{ route('customer.ready.buy', $stock) }}" class="btn btn-success">Lanjut Beli</a>
        </div>
    </div>
</div>
</body>
</html>