<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $agen->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">WhatsApp</label>
        <input type="text" name="phone_wa" class="form-control @error('phone_wa') is-invalid @enderror"
               value="{{ old('phone_wa', $agen->phone_wa ?? '') }}" required>
        @error('phone_wa')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $agen->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Kode Agen</label>
        <input type="text" name="kode_agen" class="form-control @error('kode_agen') is-invalid @enderror"
               value="{{ old('kode_agen', $agen->kode_agen ?? '') }}" required>
        @error('kode_agen')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Area</label>
        <input type="text" name="area" class="form-control @error('area') is-invalid @enderror"
               value="{{ old('area', $agen->area ?? '') }}">
        @error('area')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Alamat Lengkap</label>
        <input type="text" name="address_line" class="form-control @error('address_line') is-invalid @enderror"
               value="{{ old('address_line', $agen->address_line ?? '') }}">
        @error('address_line')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 d-flex align-items-center mt-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $agen->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
    </div>
</div>