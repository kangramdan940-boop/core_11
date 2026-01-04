<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Upload File</label>
        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">URL Path File (opsional jika upload)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-link-m"></i></span>
            <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                   value="{{ old('url', $asset->url ?? '') }}">
        </div>
        @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Judul</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-heading"></i></span>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $asset->title ?? '') }}">
        </div>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Type</label>
        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror"
               value="{{ old('type', $asset->type ?? '') }}">
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
               value="{{ old('category', $asset->category ?? '') }}">
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $asset->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @php($current = old('status', $asset->status ?? 'active'))
            <option value="active" {{ $current === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $current === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if(!empty($asset?->url))
        <div class="col-md-8">
            @php($raw = $asset->url ?? '')
            @php($src = Str::startsWith($raw, ['http://','https://']) ? $raw : asset($raw))
            @php($ext = strtolower(pathinfo($raw, PATHINFO_EXTENSION)))
            @php($isImg = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']))
            <label class="form-label d-block">Preview</label>
            @if($isImg && !empty($src))
                <a href="{{ $src }}" target="_blank"><img src="{{ $src }}" alt="asset" style="height:120px;object-fit:contain;"></a>

                
            @elseif(!empty($src))
                <a href="{{ $src }}" target="_blank">{{ $src }}</a>
            @endif
        </div>
    @endif
</div>



