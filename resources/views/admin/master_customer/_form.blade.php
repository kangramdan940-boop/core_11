<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
               value="{{ old('full_name', $customer->full_name ?? '') }}" required>
        @error('full_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">WhatsApp</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-whatsapp-line"></i></span>
            <input type="text" name="phone_wa" class="form-control @error('phone_wa') is-invalid @enderror"
                   value="{{ old('phone_wa', $customer->phone_wa ?? '') }}" required>
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
                   value="{{ old('email', $customer->email ?? '') }}" required>
        </div>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control"
               value="{{ old('nik', $customer->nik ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">No KK</label>
        <input type="text" name="no_kk" class="form-control"
               value="{{ old('no_kk', $customer->no_kk ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Tanggal Lahir</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
            <input type="text" id="basic-date-picker" name="birth_date" class="form-control"
                   placeholder="YYYY-MM-DD"
                   value="{{ old('birth_date', isset($customer->birth_date) ? $customer->birth_date->format('Y-m-d') : '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <label class="form-label">Kelurahan</label>
        <input type="text" name="kelurahan" class="form-control"
               value="{{ old('kelurahan', $customer->kelurahan ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Kecamatan</label>
        <input type="text" name="kecamatan" class="form-control"
               value="{{ old('kecamatan', $customer->kecamatan ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Kota</label>
        <input type="text" name="kota" class="form-control"
               value="{{ old('kota', $customer->kota ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Provinsi</label>
        <input type="text" name="provinsi" class="form-control"
               value="{{ old('provinsi', $customer->provinsi ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Kode Pos</label>
        <input type="text" name="kode_pos" class="form-control"
               value="{{ old('kode_pos', $customer->kode_pos ?? '') }}">
    </div>

    <div class="col-md-9">
        <label class="form-label">Alamat Lengkap</label>
        <input type="text" name="address_line" class="form-control"
               value="{{ old('address_line', $customer->address_line ?? '') }}">
    </div>

    <div class="col-md-3 d-flex align-items-center mt-4 pt-md-5">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
    </div>
</div>
