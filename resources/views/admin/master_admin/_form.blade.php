<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $admin->name ?? '') }}" required>
        </div>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">WhatsApp</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-whatsapp-line"></i></span>
            <input type="text" name="phone_wa" class="form-control @error('phone_wa') is-invalid @enderror"
                   value="{{ old('phone_wa', $admin->phone_wa ?? '') }}" required>
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
                   value="{{ old('email', $admin->email ?? '') }}" required>
        </div>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Jabatan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-briefcase-4-line"></i></span>
            @php($roles = \App\Models\SysRole::orderBy('name')->get())
            <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror">
                <option value="">-- Pilih Jabatan --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ old('jabatan', $admin->jabatan ?? '') === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('jabatan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2 d-flex align-items-center mt-2">
        <div class="form-check pt-sm-6">
            <input class="form-check-input" type="checkbox" name="is_super_admin" id="is_super_admin"
                   {{ old('is_super_admin', $admin->is_super_admin ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_super_admin">
                Super Admin
            </label>
        </div>
    </div>

    <div class="col-md-2 d-flex align-items-center mt-2">
        <div class="form-check pt-sm-6">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $admin->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
    </div>
</div>
