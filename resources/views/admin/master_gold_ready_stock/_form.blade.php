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

    <div class="col-md-4">
        <label class="form-label">Nama Produk</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-article-line"></i></span>
            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk', $stock->nama_produk ?? '') }}">
        </div>
        @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Video URL</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-video-line"></i></span>
            <input type="text" name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $stock->video_url ?? '') }}">
        </div>
        @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Deskripsi Pengiriman</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-truck-line"></i></span>
            <input type="text" name="deskripsi_pengiriman" class="form-control @error('deskripsi_pengiriman') is-invalid @enderror" value="{{ old('deskripsi_pengiriman', $stock->deskripsi_pengiriman ?? '') }}">
        </div>
        @error('deskripsi_pengiriman') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Images (satu URL per baris)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-image-line"></i></span>
            <textarea name="images" rows="3" class="form-control @error('images') is-invalid @enderror">{{ old('images', (isset($stock->images) && is_array($stock->images)) ? implode("\n", $stock->images) : ($stock->images ?? '')) }}</textarea>
        </div>
        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="mt-2">
            <select class="form-select" id="assetSuggest" onchange="(function(s){var ta=document.querySelector('textarea[name=\'images\']');var v=s.value.trim();if(ta&&v){ta.value=(ta.value?ta.value+'\n':'')+v;s.value='';}})(this)">
                <option value="">- Pilih dari Asset -</option>
                @foreach(($assets ?? []) as $as)
                    <option value="{{ $as->url }}">{{ $as->title ?? basename($as->url) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <label class="form-label">Jumlah Terjual</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-shopping-bag-3-line"></i></span>
            <input type="number" min="0" name="jumlah_terjual" class="form-control @error('jumlah_terjual') is-invalid @enderror" value="{{ old('jumlah_terjual', $stock->jumlah_terjual ?? '') }}">
        </div>
        @error('jumlah_terjual') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label">Acara</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
            <input type="text" name="acara" class="form-control @error('acara') is-invalid @enderror" value="{{ old('acara', $stock->acara ?? '') }}">
        </div>
        @error('acara') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label">Negara Asal</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-flag-line"></i></span>
            <input type="text" name="negara_asal" class="form-control @error('negara_asal') is-invalid @enderror" value="{{ old('negara_asal', $stock->negara_asal ?? '') }}">
        </div>
        @error('negara_asal') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Tags</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
            <textarea name="tags" rows="2" class="form-control @error('tags') is-invalid @enderror">{{ old('tags', $stock->tags ?? '') }}</textarea>
        </div>
        @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 d-flex align-items-center mt-4" style="gap: 24px;">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $stock->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_custom" id="is_custom" {{ old('is_custom', $stock->is_custom ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_custom">Custom</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_mystery_box" id="is_mystery_box" {{ old('is_mystery_box', $stock->is_mystery_box ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_mystery_box">Mystery Box</label>
        </div>
    </div>
</div>
