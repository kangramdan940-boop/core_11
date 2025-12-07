<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Gramasi</label>
        <select name="id_gramasi" class="form-select @error('id_gramasi') is-invalid @enderror" required>
            <option value="">-- Pilih Gramasi --</option>
            @foreach($gramasis as $g)
                <option value="{{ $g->id }}" {{ (string)old('id_gramasi', $item->id_gramasi ?? '') === (string)$g->id ? 'selected' : '' }}>
                    {{ $g->nama }} - {{ number_format($g->gramasi, 3) }} g
                </option>
            @endforeach
        </select>
        @error('id_gramasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Harga Hari Ini (IDR)</label>
        <input type="number" step="0.01" min="0" name="harga_hariini" class="form-control @error('harga_hariini') is-invalid @enderror"
               value="{{ old('harga_hariini', $item->harga_hariini ?? '') }}" required>
        @error('harga_hariini')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Harga Jasa (IDR)</label>
        <input type="number" step="0.01" min="0" name="harga_jasa" class="form-control @error('harga_jasa') is-invalid @enderror"
               value="{{ old('harga_jasa', $item->harga_jasa ?? '') }}">
        @error('harga_jasa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Urutan Jasa</label>
        <input type="number" step="1" min="0" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
               value="{{ old('urutan', $item->urutan ?? '') }}">
        @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label d-block">Izin Transaksi</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="is_allow_ready" id="is_allow_ready"
                   {{ old('is_allow_ready', $item->is_allow_ready ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_allow_ready">Allow Ready</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="is_allow_po" id="is_allow_po"
                   {{ old('is_allow_po', $item->is_allow_po ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_allow_po">Allow PO</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Upload Gambar Produk</label>
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">URL Path Gambar (opsional)</label>
        <input type="text" name="image_produk" class="form-control @error('image_produk') is-invalid @enderror"
               value="{{ old('image_produk', $item->image_produk ?? '') }}">
        @error('image_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Expired DAE</label>
        <input type="date" name="expired_dae" class="form-control @error('expired_dae') is-invalid @enderror"
               value="{{ old('expired_dae', isset($item->expired_dae) ? $item->expired_dae->format('Y-m-d') : '') }}">
        @error('expired_dae')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            @php $statusVal = old('status', $item->status ?? 'active'); @endphp
            <option value="active" {{ $statusVal === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ $statusVal === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if(!empty($item?->image_produk))
        <div class="col-md-12">
            <label class="form-label d-block">Preview</label>
            <img src="{{ Str::startsWith($item->image_produk, ['http://','https://']) ? $item->image_produk : asset($item->image_produk) }}"
                 alt="produk" style="height:60px;object-fit:contain;">
        </div>
    @endif
</div>