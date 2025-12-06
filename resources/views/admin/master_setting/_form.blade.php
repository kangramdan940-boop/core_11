<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Key</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-key-2-line"></i></span>
            <input type="text" name="key" class="form-control @error('key') is-invalid @enderror"
                   value="{{ old('key', $setting->key ?? '') }}" required>
        </div>
        @error('key') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Label</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
            <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                   value="{{ old('label', $setting->label ?? '') }}">
        </div>
        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Group</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-folders-line"></i></span>
            <input type="text" name="group" class="form-control @error('group') is-invalid @enderror"
                   value="{{ old('group', $setting->group ?? '') }}">
        </div>
        @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Value</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
            <textarea name="value" rows="3" class="form-control @error('value') is-invalid @enderror">{{ old('value', $setting->value ?? '') }}</textarea>
        </div>
        @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 d-flex align-items-center mt-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $setting->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
    </div>
</div>
