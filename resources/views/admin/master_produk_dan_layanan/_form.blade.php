<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Gramasi</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-scales-3-line"></i></span>
            <select name="id_gramasi" class="form-select @error('id_gramasi') is-invalid @enderror" required>
                <option value="">-- Pilih Gramasi --</option>
                @foreach($gramasis as $g)
                    <option value="{{ $g->id }}" {{ (string)old('id_gramasi', $item->id_gramasi ?? '') === (string)$g->id ? 'selected' : '' }}>
                        {{ $g->nama }} - {{ number_format($g->gramasi, 3) }} g
                    </option>
                @endforeach
            </select>
        </div>
        @error('id_gramasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Harga Hari Ini (IDR)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-cash-line"></i></span>
            <input type="number" step="0.01" min="0" inputmode="numeric" name="harga_hariini" class="form-control @error('harga_hariini') is-invalid @enderror"
                   value="{{ old('harga_hariini', $item->harga_hariini ?? '') }}" required>
        </div>
        @error('harga_hariini')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Harga Jasa (IDR)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-cash-line"></i></span>
            <input type="number" step="0.01" min="0" inputmode="numeric" name="harga_jasa" class="form-control @error('harga_jasa') is-invalid @enderror"
                   value="{{ old('harga_jasa', $item->harga_jasa ?? '') }}">
        </div>
        @error('harga_jasa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Urutan Jasa</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-list-ordered"></i></span>
            <input type="number" step="1" min="0" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                   value="{{ old('urutan', $item->urutan ?? '') }}">
        </div>
        @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label d-block">Izin Transaksi</label>
        <div class="form-check form-check-inline mt-sm-2">
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
        <div class="input-group">
            <span class="input-group-text"><i class="ri-link-m"></i></span>
            <input type="text" name="image_produk" class="form-control @error('image_produk') is-invalid @enderror"
                   value="{{ old('image_produk', $item->image_produk ?? '') }}">
        </div>
        @error('image_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Expired DATE</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
            <input type="text" id="basic-date-picker" name="expired_date" class="form-control @error('expired_date') is-invalid @enderror"
                   placeholder="YYYY-MM-DD"
                   value="{{ old('expired_date', isset($item->expired_date) ? $item->expired_date->format('Y-m-d') : '') }}">
        </div>
        @error('expired_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Status</label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-checkbox-circle-line"></i></span>
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                @php $statusVal = old('status', $item->status ?? 'active'); @endphp
                <option value="active" {{ $statusVal === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ $statusVal === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
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
