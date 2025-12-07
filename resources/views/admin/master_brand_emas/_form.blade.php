<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Kode Brand</label>
        <input type="text" name="kode_brand" class="form-control @error('kode_brand') is-invalid @enderror"
               value="{{ old('kode_brand', $brand->kode_brand ?? '') }}" required>
        @error('kode_brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-5">
        <label class="form-label">Nama Brand</label>
        <input type="text" name="nama_brand" class="form-control @error('nama_brand') is-invalid @enderror"
               value="{{ old('nama_brand', $brand->nama_brand ?? '') }}" required>
        @error('nama_brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Upload Gambar</label>
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">URL Path Gambar (opsional)</label>
        <input type="text" name="image_url" class="form-control @error('image_url') is-invalid @enderror"
               value="{{ old('image_url', $brand->image_url ?? '') }}">
        @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $brand->deskripsi ?? '') }}</textarea>
        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if(!empty($brand?->image_url))
        <div class="col-md-12">
            <label class="form-label d-block">Preview</label>
            <img src="{{ Str::startsWith($brand->image_url, ['http://','https://']) ? $brand->image_url : asset($brand->image_url) }}"
                 alt="brand" style="height:60px;object-fit:contain;">
        </div>
    @endif
</div>