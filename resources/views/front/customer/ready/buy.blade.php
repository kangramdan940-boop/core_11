<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beli Emas Ready - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('customer.ready.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h6 mb-3">Beli Emas Ready</h1>

            <div class="mb-3">
                <strong>Item</strong>: {{ $stock->kode_item }} • {{ strtoupper($stock->brand) }} • {{ number_format((float)$stock->gramasi, 3, ',', '.') }} g
                <br>
                <strong>Harga/jual</strong>: {{ number_format((float)($stock->harga_jual_fix ?? $stock->harga_jual_minimal ?? 0), 2, ',', '.') }} IDR
            </div>

            <form action="{{ route('customer.ready.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ready_stock_id" value="{{ $stock->id }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Qty</label>
                        <input type="number" name="qty" min="1" step="1" value="1" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipe Penerimaan</label>
                        <select name="delivery_type" class="form-select">
                            <option value="ship">Dikirim</option>
                            <option value="pickup">Ambil di Agen</option>
                            <option value="titip_agen">Titip di Agen</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted">Isi data pengiriman jika memilih "Dikirim".</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="shipping_name" class="form-control" value="{{ old('shipping_name', $customer->full_name ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">HP Penerima</label>
                        <input type="text" name="shipping_phone" class="form-control" value="{{ old('shipping_phone', $customer->phone_wa ?? '') }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="shipping_address" class="form-control" value="{{ old('shipping_address', $customer->address_line ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kota</label>
                        <input type="text" name="shipping_city" class="form-control" value="{{ old('shipping_city', $customer->kota ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="shipping_province" class="form-control" value="{{ old('shipping_province', $customer->provinsi ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="shipping_postal_code" class="form-control" value="{{ old('shipping_postal_code', $customer->kode_pos ?? '') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-success">Buat Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>