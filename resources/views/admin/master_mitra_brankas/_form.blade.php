<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                   value="{{ old('nama_lengkap', $mitra->nama_lengkap ?? '') }}" required>
        </div>
        @error('nama_lengkap')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">WhatsApp</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-whatsapp-line"></i></span>
            <input type="text" name="phone_wa" class="form-control @error('phone_wa') is-invalid @enderror"
                   value="{{ old('phone_wa', $mitra->phone_wa ?? '') }}" required>
        </div>
        @error('phone_wa')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-mail-line"></i></span>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $mitra->email ?? '') }}" required>
        </div>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Kode Mitra</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-key-2-line"></i></span>
            <input type="text" name="kode_mitra" class="form-control @error('kode_mitra') is-invalid @enderror"
                   value="{{ old('kode_mitra', $mitra->kode_mitra ?? '') }}" required>
        </div>
        @error('kode_mitra')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Platform</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-apps-2-line"></i></span>
            <input type="text" name="platform" class="form-control @error('platform') is-invalid @enderror"
                   value="{{ old('platform', $mitra->platform ?? 'brankas_antam') }}" required>
        </div>
        @error('platform')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Account No</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-bank-card-line"></i></span>
            <input type="text" name="account_no" class="form-control @error('account_no') is-invalid @enderror"
                   value="{{ old('account_no', $mitra->account_no ?? '') }}">
        </div>
        @error('account_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Limit Harian (gram)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-scales-3-line"></i></span>
            <input type="number" step="0.001" min="0" name="harian_limit_gram"
                   class="form-control @error('harian_limit_gram') is-invalid @enderror"
                   value="{{ old('harian_limit_gram', $mitra->harian_limit_gram ?? '') }}" required>
        </div>
        @error('harian_limit_gram')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Komisi (%)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-percent-line"></i></span>
            <input type="number" step="0.01" min="0" max="100" name="komisi_persen"
                   class="form-control @error('komisi_persen') is-invalid @enderror"
                   value="{{ old('komisi_persen', $mitra->komisi_persen ?? '') }}" required>
        </div>
        @error('komisi_persen')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 d-flex align-items-center mt-2" style="gap: 24px;">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $mitra->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_edit" id="is_edit"
                   {{ old('is_edit', $mitra->is_edit ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_edit">
                Boleh di-edit
            </label>
        </div>
    </div>
</div>
