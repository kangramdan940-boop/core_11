<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nomor Rekening</label>
        <input type="text" name="rekening_nomor" class="form-control @error('rekening_nomor') is-invalid @enderror"
               value="{{ old('rekening_nomor', $setting->rekening_nomor ?? '') }}">
        @error('rekening_nomor')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Nama Bank</label>
        <input type="text" name="bank_nama" class="form-control @error('bank_nama') is-invalid @enderror"
               value="{{ old('bank_nama', $setting->bank_nama ?? '') }}">
        @error('bank_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Atas Nama</label>
        <input type="text" name="rekening_atas_nama" class="form-control @error('rekening_atas_nama') is-invalid @enderror"
               value="{{ old('rekening_atas_nama', $setting->rekening_atas_nama ?? '') }}">
        @error('rekening_atas_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Kadaluarsa Pembayaran (menit)</label>
        <input type="number" min="1" name="expired_minutes" class="form-control @error('expired_minutes') is-invalid @enderror"
               value="{{ old('expired_minutes', $setting->expired_minutes ?? 1440) }}">
        @error('expired_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Petunjuk Konfirmasi Pembayaran</label>
        <textarea name="konfirmasi_petunjuk" rows="3" class="form-control @error('konfirmasi_petunjuk') is-invalid @enderror">{{ old('konfirmasi_petunjuk', $setting->konfirmasi_petunjuk ?? '') }}</textarea>
        @error('konfirmasi_petunjuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Syarat dan Ketentuan</label>
        <textarea name="syarat_ketentuan" rows="3" class="form-control @error('syarat_ketentuan') is-invalid @enderror">{{ old('syarat_ketentuan', $setting->syarat_ketentuan ?? '') }}</textarea>
        @error('syarat_ketentuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Informasi Jasa Titip dan Ketentuan</label>
        <textarea name="jasa_titip_informasi" rows="3" class="form-control @error('jasa_titip_informasi') is-invalid @enderror">{{ old('jasa_titip_informasi', $setting->jasa_titip_informasi ?? '') }}</textarea>
        @error('jasa_titip_informasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>