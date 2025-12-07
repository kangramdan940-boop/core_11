<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Upload Gambar</label>
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">URL Path Gambar (opsional jika upload)</label>
        <input type="text" name="image_url" class="form-control @error('image_url') is-invalid @enderror"
               value="{{ old('image_url', $menu->image ?? '') }}">
        @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Wajib diisi jika tidak upload gambar.</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">Label</label>
        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
               value="{{ old('label', $menu->label ?? '') }}" required>
        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Path URL (opsional)</label>
        <input type="text" name="path_url" class="form-control @error('path_url') is-invalid @enderror"
               value="{{ old('path_url', $menu->path_url ?? '') }}">
        @error('path_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if(!empty($menu?->image))
        <div class="col-md-12">
            <label class="form-label d-block">Preview</label>
            <img src="{{ Str::startsWith($menu->image, ['http://','https://']) ? $menu->image : asset($menu->image) }}"
                 alt="menu" style="height:120px;object-fit:cover;">
        </div>
    @endif
</div>