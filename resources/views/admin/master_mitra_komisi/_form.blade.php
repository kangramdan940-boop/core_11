<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Mitra Brankas</label>
        <select name="master_mitra_brankas_id" class="form-select @error('master_mitra_brankas_id') is-invalid @enderror">
            <option value="">- Tanpa Mitra -</option>
            @foreach($mitras as $m)
                <option value="{{ $m->id }}"
                    {{ (string)old('master_mitra_brankas_id', $komisi->master_mitra_brankas_id ?? '') === (string)$m->id ? 'selected' : '' }}>
                    {{ $m->nama_lengkap }} ({{ $m->kode_mitra }})
                </option>
            @endforeach
        </select>
        @error('master_mitra_brankas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Tipe Transaksi</label>
        <input type="text" name="tipe_transaksi" class="form-control @error('tipe_transaksi') is-invalid @enderror"
               value="{{ old('tipe_transaksi', $komisi->tipe_transaksi ?? 'po') }}" required>
        @error('tipe_transaksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Komisi (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="komisi_persen" class="form-control @error('komisi_persen') is-invalid @enderror"
               value="{{ old('komisi_persen', $komisi->komisi_persen ?? '') }}" required>
        @error('komisi_persen') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Berlaku Mulai</label>
        <input type="date" name="berlaku_mulai" class="form-control @error('berlaku_mulai') is-invalid @enderror"
               value="{{ old('berlaku_mulai', isset($komisi->berlaku_mulai) ? $komisi->berlaku_mulai->format('Y-m-d') : '') }}">
        @error('berlaku_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Berlaku Sampai</label>
        <input type="date" name="berlaku_sampai" class="form-control @error('berlaku_sampai') is-invalid @enderror"
               value="{{ old('berlaku_sampai', isset($komisi->berlaku_sampai) ? $komisi->berlaku_sampai->format('Y-m-d') : '') }}">
        @error('berlaku_sampai') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $komisi->catatan ?? '') }}</textarea>
        @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 d-flex align-items-center mt-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $komisi->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Aktif
            </label>
        </div>
    </div>
</div>