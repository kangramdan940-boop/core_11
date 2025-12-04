<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat PO Emas - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h6 mb-3">Buat PO Emas</h1>

            <form action="{{ route('customer.po.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Harga per Gram (IDR)</label>
                        <input type="number" name="harga_per_gram" step="0.01" class="form-control" placeholder="contoh: 1200000" value="{{ old('harga_per_gram') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Gram</label>
                        <input type="number" name="total_gram" step="0.001" class="form-control" placeholder="contoh: 1.500" value="{{ old('total_gram') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipe Penerimaan</label>
                        <select class="form-select" id="deliveryType" name="delivery_type">
                            <option value="ship" {{ old('delivery_type')==='ship' ? 'selected' : '' }}>Dikirim</option>
                            <option value="pickup" {{ old('delivery_type')==='pickup' ? 'selected' : '' }}>Ambil di Agen</option>
                            <option value="titip_agen" {{ old('delivery_type')==='titip_agen' ? 'selected' : '' }}>Titip di Agen</option>
                        </select>
                    </div>

                    <div class="col-12" id="shippingFields">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" name="shipping_name" class="form-control" placeholder="Nama penerima" value="{{ old('shipping_name', $customer->full_name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="shipping_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('shipping_phone', $customer->phone_wa ?? '') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="shipping_address" class="form-control" placeholder="Alamat lengkap" value="{{ old('shipping_address', $customer->address_line ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kota</label>
                                <input type="text" name="shipping_city" class="form-control" placeholder="Kota" value="{{ old('shipping_city', $customer->kota ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Provinsi</label>
                                <input type="text" name="shipping_province" class="form-control" placeholder="Provinsi" value="{{ old('shipping_province', $customer->provinsi ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" name="shipping_postal_code" class="form-control" placeholder="Kode Pos" value="{{ old('shipping_postal_code', $customer->kode_pos ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Submit PO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const select = document.getElementById('deliveryType');
        const fields = document.getElementById('shippingFields');
        const toggle = () => {
            fields.style.display = select.value === 'ship' ? 'block' : 'none';
        };
        select.addEventListener('change', toggle);
        toggle();
    })();
</script>
</body>
</html>