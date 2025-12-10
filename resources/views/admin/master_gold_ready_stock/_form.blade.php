<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Agen</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
            <select name="master_agen_id" class="form-select @error('master_agen_id') is-invalid @enderror">
                <option value="">- Tanpa Agen -</option>
                @foreach($agens as $a)
                    <option value="{{ $a->id }}"
                        {{ (string)old('master_agen_id', $stock->master_agen_id ?? '') === (string)$a->id ? 'selected' : '' }}>
                        {{ $a->name }} ({{ $a->kode_agen }})
                    </option>
                @endforeach
            </select>
        </div>
        @error('master_agen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Kode Item</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
            <input type="text" name="kode_item" class="form-control @error('kode_item') is-invalid @enderror"
                   value="{{ old('kode_item', $stock->kode_item ?? '') }}" required>
        </div>
        @error('kode_item') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Brand</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-medal-line"></i></span>
            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                   value="{{ old('brand', $stock->brand ?? 'antam') }}" required>
        </div>
        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Gramasi (gram)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-weight-line"></i></span>
            <input type="number" step="0.001" min="0.001" name="gramasi" class="form-control @error('gramasi') is-invalid @enderror"
                   value="{{ old('gramasi', $stock->gramasi ?? '') }}" required>
        </div>
        @error('gramasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Nomor Seri</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-barcode-line"></i></span>
            <input type="text" name="nomor_seri" class="form-control @error('nomor_seri') is-invalid @enderror"
                   value="{{ old('nomor_seri', $stock->nomor_seri ?? '') }}">
        </div>
        @error('nomor_seri') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label">Tahun Cetak</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-calendar-2-line"></i></span>
            <input type="number" name="tahun_cetak" class="form-control @error('tahun_cetak') is-invalid @enderror"
                   value="{{ old('tahun_cetak', $stock->tahun_cetak ?? '') }}">
        </div>
        @error('tahun_cetak') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label">Kondisi</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-shield-check-line"></i></span>
            <select name="kondisi_barang" class="form-select @error('kondisi_barang') is-invalid @enderror" required>
                @php($cond = old('kondisi_barang', $stock->kondisi_barang ?? 'mint'))
                <option value="mint" {{ $cond === 'mint' ? 'selected' : '' }}>Mint</option>
                <option value="second" {{ $cond === 'second' ? 'selected' : '' }}>Second</option>
            </select>
        </div>
        @error('kondisi_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label">Status</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-information-line"></i></span>
            @php($st = old('status', $stock->status ?? 'available'))
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="available" {{ $st === 'available' ? 'selected' : '' }}>Available</option>
                <option value="reserved"  {{ $st === 'reserved' ? 'selected' : '' }}>Reserved</option>
                <option value="sold"      {{ $st === 'sold' ? 'selected' : '' }}>Sold</option>
            </select>
        </div>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga Beli</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-money-dollar-circle-line"></i></span>
            <input type="number" step="0.01" min="0" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror"
                   value="{{ old('harga_beli', $stock->harga_beli ?? '') }}">
        </div>
        @error('harga_beli') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga Jual Minimal</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-money-dollar-circle-line"></i></span>
            <input type="number" step="0.01" min="0" name="harga_jual_minimal" class="form-control @error('harga_jual_minimal') is-invalid @enderror"
                   value="{{ old('harga_jual_minimal', $stock->harga_jual_minimal ?? '') }}">
        </div>
        @error('harga_jual_minimal') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga Jual Fix</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-money-dollar-circle-line"></i></span>
            <input type="number" step="0.01" min="0" name="harga_jual_fix" class="form-control @error('harga_jual_fix') is-invalid @enderror"
                   value="{{ old('harga_jual_fix', $stock->harga_jual_fix ?? '') }}">
        </div>
        @error('harga_jual_fix') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Lokasi Simpan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
            <input type="text" name="lokasi_simpan" class="form-control @error('lokasi_simpan') is-invalid @enderror"
                   value="{{ old('lokasi_simpan', $stock->lokasi_simpan ?? '') }}">
        </div>
        @error('lokasi_simpan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-9">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $stock->catatan ?? '') }}</textarea>
        @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 d-flex align-items-center mt-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $stock->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>
