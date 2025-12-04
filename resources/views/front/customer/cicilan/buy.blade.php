<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Kontrak Cicilan - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Buat Kontrak Cicilan</h1>
        <a href="{{ route('customer.cicilan.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-2">Detail Emas</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode Item</strong><br>{{ $stock->kode_item }}</div>
                <div class="col-md-3"><strong>Brand</strong><br>{{ $stock->brand }}</div>
                <div class="col-md-3"><strong>Gramasi</strong><br>{{ $stock->gramasi }}</div>
                <div class="col-md-3"><strong>Harga Jual</strong><br>{{ number_format((float)$stock->harga_jual_fix, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-2">Form Kontrak</h6>
            <form action="{{ route('customer.cicilan.store') }}" method="POST" class="row g-3">
                @csrf
                <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                <div class="col-md-4">
                    <label class="form-label">Tenor (bulan)</label>
                    <input type="number" name="tenor_bulan" min="3" max="24" value="12" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">DP (%)</label>
                    <input type="number" step="0.01" name="dp_persen" min="0" max="50" value="20" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe Pengiriman</label>
                    <select name="delivery_type" class="form-select" required>
                        <option value="ship">Kirim</option>
                        <option value="pickup">Ambil di Agen</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="alert alert-info py-2">
                        Pengiriman dilakukan setelah kontrak lunas.
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Penerima</label>
                    <input type="text" name="shipping_name" class="form-control" value="{{ $customer->full_name ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="shipping_phone" class="form-control" value="{{ $customer->phone ?? '' }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="shipping_address" class="form-control" rows="2">{{ $customer->address ?? '' }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kota</label>
                    <input type="text" name="shipping_city" class="form-control" value="{{ $customer->city ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Provinsi</label>
                    <input type="text" name="shipping_province" class="form-control" value="{{ $customer->province ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="shipping_postal_code" class="form-control" value="">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Buat Kontrak</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>