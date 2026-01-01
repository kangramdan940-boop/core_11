<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Upload Icon Login</label>
        <input type="file" name="login_icon" accept="image/*" class="form-control @error('login_icon') is-invalid @enderror">
        @error('login_icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">URL Path Icon Login (opsional jika upload)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-link-m"></i></span>
            <input type="text" name="login_page_icon" class="form-control @error('login_page_icon') is-invalid @enderror"
                   value="{{ old('login_page_icon', $config->login_page_icon ?? '') }}">
        </div>
        @error('login_page_icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Wajib diisi jika tidak upload icon.</small>
    </div>

    <div class="col-md-12">
        <label class="form-label d-block">Preview Icon</label>
        @php($raw = $config->login_page_icon ?? '')
        @php($imgUrl = Str::startsWith($raw, ['http://','https://']) ? $raw : ($raw ? asset($raw) : ''))
        @if(!empty($raw))
            <img src="{{ $imgUrl }}" alt="login icon" style="height:80px;object-fit:contain;">
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Link Informasi</label>
        <input type="text" name="information_link" class="form-control @error('information_link') is-invalid @enderror"
               value="{{ old('information_link', $config->information_link ?? '') }}">
        @error('information_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Development Mode</label>
        <select name="development_mode" class="form-select @error('development_mode') is-invalid @enderror">
            @php($v = old('development_mode', isset($config) ? (int)$config->development_mode : ''))
            <option value="" {{ $v === '' ? 'selected' : '' }}>Default (null)</option>
            <option value="1" {{ $v === 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $v === 0 ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('development_mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Status Naik</label>
        <select name="status_naik" class="form-select @error('status_naik') is-invalid @enderror">
            @php($v = old('status_naik', isset($config) ? (int)$config->status_naik : ''))
            <option value="" {{ $v === '' ? 'selected' : '' }}>Default (null)</option>
            <option value="1" {{ $v === 1 ? 'selected' : '' }}>Ya</option>
            <option value="0" {{ $v === 0 ? 'selected' : '' }}>Tidak</option>
        </select>
        @error('status_naik')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Status Turun</label>
        <select name="status_turun" class="form-select @error('status_turun') is-invalid @enderror">
            @php($v = old('status_turun', isset($config) ? (int)$config->status_turun : ''))
            <option value="" {{ $v === '' ? 'selected' : '' }}>Default (null)</option>
            <option value="1" {{ $v === 1 ? 'selected' : '' }}>Ya</option>
            <option value="0" {{ $v === 0 ? 'selected' : '' }}>Tidak</option>
        </select>
        @error('status_turun')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Title Welcome</label>
        <input type="text" name="welcome_title" class="form-control @error('welcome_title') is-invalid @enderror"
               value="{{ old('welcome_title', $config->welcome_title ?? '') }}">
        @error('welcome_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Description Welcome</label>
        <textarea name="welcome_description" rows="3" class="form-control @error('welcome_description') is-invalid @enderror">{{ old('welcome_description', $config->welcome_description ?? '') }}</textarea>
        @error('welcome_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Broadcast Info Banner Status</label>
        <select name="broadcast_info_banner_status" class="form-select @error('broadcast_info_banner_status') is-invalid @enderror">
            @php($v = old('broadcast_info_banner_status', isset($config) ? (int)$config->broadcast_info_banner_status : ''))
            <option value="" {{ $v === '' ? 'selected' : '' }}>Default (null)</option>
            <option value="1" {{ $v === 1 ? 'selected' : '' }}>Tampilkan</option>
            <option value="0" {{ $v === 0 ? 'selected' : '' }}>Sembunyikan</option>
        </select>
        @error('broadcast_info_banner_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Broadcast Info Banner Description</label>
        <textarea name="broadcast_info_banner_description" rows="3" class="form-control @error('broadcast_info_banner_description') is-invalid @enderror">{{ old('broadcast_info_banner_description', $config->broadcast_info_banner_description ?? '') }}</textarea>
        @error('broadcast_info_banner_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>