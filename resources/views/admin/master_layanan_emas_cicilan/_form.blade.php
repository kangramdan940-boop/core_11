<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Kode Layanan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
            <input type="text" name="kode_layanan" class="form-control @error('kode_layanan') is-invalid @enderror"
                   value="{{ old('kode_layanan', $item->kode_layanan ?? '') }}" maxlength="50" required>
        </div>
        @error('kode_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label class="form-label">Nama Layanan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-hand-heart-line"></i></span>
            <input type="text" name="nama_layanan" class="form-control @error('nama_layanan') is-invalid @enderror"
                   value="{{ old('nama_layanan', $item->nama_layanan ?? '') }}" maxlength="150" required>
        </div>
        @error('nama_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Tenor Min (bulan)</label>
        <input type="number" name="tenor_min_bulan" min="1" max="60"
               class="form-control @error('tenor_min_bulan') is-invalid @enderror"
               value="{{ old('tenor_min_bulan', $item->tenor_min_bulan ?? 3) }}" required>
        @error('tenor_min_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Tenor Max (bulan)</label>
        <input type="number" name="tenor_max_bulan" min="1" max="60"
               class="form-control @error('tenor_max_bulan') is-invalid @enderror"
               value="{{ old('tenor_max_bulan', $item->tenor_max_bulan ?? 12) }}" required>
        @error('tenor_max_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">DP Min (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="dp_min_persen"
               class="form-control @error('dp_min_persen') is-invalid @enderror"
               value="{{ old('dp_min_persen', $item->dp_min_persen ?? 20) }}" required>
        @error('dp_min_persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">DP Max (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="dp_max_persen"
               class="form-control @error('dp_max_persen') is-invalid @enderror"
               value="{{ old('dp_max_persen', $item->dp_max_persen ?? 50) }}" required>
        @error('dp_max_persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Margin Flat (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="margin_persen"
               class="form-control @error('margin_persen') is-invalid @enderror"
               value="{{ old('margin_persen', $item->margin_persen ?? '') }}">
        @error('margin_persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-9">
        <label class="form-label">Margin per Tenor (opsional)</label>
        <div class="row g-2">
            @php
                $mk = old('margin_konfigurasi', $item->margin_konfigurasi ?? []);
            @endphp
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">3 bln</span>
                    <input type="number" step="0.01" min="0" max="100" name="margin_konfigurasi[3]"
                           class="form-control"
                           value="{{ is_array($mk) ? ($mk[3] ?? '') : '' }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">6 bln</span>
                    <input type="number" step="0.01" min="0" max="100" name="margin_konfigurasi[6]"
                           class="form-control"
                           value="{{ is_array($mk) ? ($mk[6] ?? '') : '' }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">12 bln</span>
                    <input type="number" step="0.01" min="0" max="100" name="margin_konfigurasi[12]"
                           class="form-control"
                           value="{{ is_array($mk) ? ($mk[12] ?? '') : '' }}">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <label class="form-label">Biaya Admin (Rp)</label>
        <input type="number" step="0.01" min="0" name="biaya_admin"
               class="form-control @error('biaya_admin') is-invalid @enderror"
               value="{{ old('biaya_admin', $item->biaya_admin ?? 0) }}">
        @error('biaya_admin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Denda (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="denda_terlambat_persen"
               class="form-control @error('denda_terlambat_persen') is-invalid @enderror"
               value="{{ old('denda_terlambat_persen', $item->denda_terlambat_persen ?? '') }}">
        @error('denda_terlambat_persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Denda Fixed (Rp)</label>
        <input type="number" step="0.01" min="0" name="denda_terlambat_fixed"
               class="form-control @error('denda_terlambat_fixed') is-invalid @enderror"
               value="{{ old('denda_terlambat_fixed', $item->denda_terlambat_fixed ?? '') }}">
        @error('denda_terlambat_fixed')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Grace Period (hari)</label>
        <input type="number" min="0" max="31" name="grace_period_hari"
               class="form-control @error('grace_period_hari') is-invalid @enderror"
               value="{{ old('grace_period_hari', $item->grace_period_hari ?? 3) }}">
        @error('grace_period_hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Metode Pengiriman Diizinkan</label>
        @php
            $allowed = (array) old('allowed_delivery_types', $item->allowed_delivery_types ?? []);
        @endphp
        <div class="hstack gap-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="allowed_delivery_types[]"
                       value="ship" id="allowed_ship" {{ in_array('ship', $allowed, true) ? 'checked' : '' }}>
                <label class="form-check-label" for="allowed_ship">Kirim (Ship)</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="allowed_delivery_types[]"
                       value="pickup" id="allowed_pickup" {{ in_array('pickup', $allowed, true) ? 'checked' : '' }}>
                <label class="form-check-label" for="allowed_pickup">Ambil (Pickup)</label>
            </div>
        </div>
        @error('allowed_delivery_types')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 d-flex align-items-center mt-4 pt-md-5">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $item->catatan ?? '') }}</textarea>
        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>