<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $agen->name ?? '') }}" required>
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
                   value="{{ old('phone_wa', $agen->phone_wa ?? '') }}" required>
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
                   value="{{ old('email', $agen->email ?? '') }}" required>
        </div>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Kode Agen</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-key-2-line"></i></span>
            <input type="text" name="kode_agen" class="form-control @error('kode_agen') is-invalid @enderror"
                   value="{{ old('kode_agen', $agen->kode_agen ?? '') }}" required>
        </div>
        @error('kode_agen')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Area</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
            <input type="text" name="area" class="form-control @error('area') is-invalid @enderror"
                   value="{{ old('area', $agen->area ?? '') }}">
        </div>
        @error('area')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Nomor Rekening</label>
        <input type="text" name="rekening_nomor" class="form-control @error('rekening_nomor') is-invalid @enderror"
               value="{{ old('rekening_nomor', $agen->rekening_nomor ?? '') }}">
        @error('rekening_nomor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Alamat Lengkap</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-home-4-line"></i></span>
            <input type="text" name="address_line" class="form-control @error('address_line') is-invalid @enderror"
                   value="{{ old('address_line', $agen->address_line ?? '') }}">
        </div>
        @error('address_line')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 d-flex align-items-center mt-2">
        <div class="form-check me-4">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $agen->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="create_login" id="create_login" {{ old('create_login') ? 'checked' : '' }}>
            <label class="form-check-label" for="create_login">
                Buat akun login agen (sys_user)
            </label>
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <label class="form-label">Password Login Agen</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mt-3">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
    </div>

    @section('js')
        <script>
        (function(){
        var cb = document.getElementById('create_login');
        var pass = document.querySelector('input[name="password"]');
        var conf = document.querySelector('input[name="password_confirmation"]');
        function toggle(){
            var on = cb && cb.checked;
            if(pass){ pass.disabled = !on; pass.required = on; }
            if(conf){ conf.disabled = !on; conf.required = on; }
        }
        toggle();
        if(cb){ cb.addEventListener('change', toggle); }
        })();
        </script>
    @endsection
</div>
