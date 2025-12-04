<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Tanggal Harga</label>
        <input type="date" name="price_date" class="form-control @error('price_date') is-invalid @enderror"
               value="{{ old('price_date', isset($price->price_date) ? $price->price_date->format('Y-m-d') : '') }}" required>
        @error('price_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Source</label>
        <input type="text" name="source" class="form-control @error('source') is-invalid @enderror"
               value="{{ old('source', $price->source ?? 'global') }}" required>
        @error('source')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga Beli (per gram)</label>
        <input type="number" step="0.01" min="0" name="price_buy" class="form-control @error('price_buy') is-invalid @enderror"
               value="{{ old('price_buy', $price->price_buy ?? '') }}" required>
        @error('price_buy')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga Jual (per gram)</label>
        <input type="number" step="0.01" min="0" name="price_sell" class="form-control @error('price_sell') is-invalid @enderror"
               value="{{ old('price_sell', $price->price_sell ?? '') }}" required>
        @error('price_sell')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga Buyback (per gram)</label>
        <input type="number" step="0.01" min="0" name="price_buyback" class="form-control @error('price_buyback') is-invalid @enderror"
               value="{{ old('price_buyback', $price->price_buyback ?? '') }}">
        @error('price_buyback')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-9">
        <label class="form-label">Catatan</label>
        <textarea name="note" rows="2" class="form-control @error('note') is-invalid @enderror">{{ old('note', $price->note ?? '') }}</textarea>
        @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 d-flex align-items-center mt-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $price->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
    </div>
</div>