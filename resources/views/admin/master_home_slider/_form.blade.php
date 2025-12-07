<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Upload Gambar</label>
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">URL Path Gambar (opsional jika upload)</label>
        <input type="text" name="image_url" class="form-control @error('image_url') is-invalid @enderror"
               value="{{ old('image_url', $slider->image_url ?? '') }}">
        @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Wajib diisi jika tidak upload gambar.</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">Judul</label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $slider->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $slider->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if(!empty($slider?->image_url))
        <div class="col-md-12">
            <label class="form-label d-block">Preview</label>
            <img src="{{ Str::startsWith($slider->image_url, ['http://','https://']) ? $slider->image_url : asset($slider->image_url) }}"
                 alt="slider" style="height:120px;object-fit:cover;">
        </div>
    @endif
</div>