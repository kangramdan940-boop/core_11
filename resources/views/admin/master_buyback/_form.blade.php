@php
    $item = $item ?? null;
@endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Brand</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                   value="{{ old('brand', $item->brand ?? '') }}" required>
        </div>
        @error('brand')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Berat</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-scales-3-line"></i></span>
            <input type="text" name="berat" class="form-control @error('berat') is-invalid @enderror"
                   value="{{ old('berat', $item->berat ?? '') }}" placeholder="mis. 5 gr" required>
        </div>
        @error('berat')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Icon (opsional)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-image-line"></i></span>
            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                   value="{{ old('icon', $item->icon ?? '') }}" placeholder="mis. fa fa-certificate">
        </div>
        @error('icon')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Harga (per unit)</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" step="1" min="0" name="harga" class="form-control @error('harga') is-invalid @enderror"
                   value="{{ old('harga', $item->harga ?? '') }}" required>
        </div>
        @error('harga')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Harga Buyback (per unit)</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" step="1" min="0" name="buyback" class="form-control @error('buyback') is-invalid @enderror"
                   value="{{ old('buyback', $item->buyback ?? '') }}" required>
        </div>
        @error('buyback')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Stok</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-archive-line"></i></span>
            <input type="text" name="stok" class="form-control @error('stok') is-invalid @enderror"
                   value="{{ old('stok', $item->stok ?? 'Tersedia') }}" required>
        </div>
        @error('stok')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-checkbox-circle-line"></i></span>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror"
                   value="{{ old('status', $item->status ?? 'Aktif') }}" placeholder="mis. Cepat" required>
        </div>
        @error('status')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>
