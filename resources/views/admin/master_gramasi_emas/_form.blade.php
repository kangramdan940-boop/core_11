<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Nama Brand</label>
        <select name="nama" class="form-select @error('nama') is-invalid @enderror" required>
            <option value="">-- Pilih Brand --</option>
            @foreach($brands as $b)
                <option value="{{ $b->nama_brand }}" {{ old('nama', $item->nama ?? '') === $b->nama_brand ? 'selected' : '' }}>{{ $b->nama_brand }}</option>
            @endforeach
        </select>
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Gramasi (gram)</label>
        <input type="number" step="0.001" min="0.001" name="gramasi" class="form-control @error('gramasi') is-invalid @enderror"
               value="{{ old('gramasi', $item->gramasi ?? '') }}" required>
        @error('gramasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $item->catatan ?? '') }}</textarea>
        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>