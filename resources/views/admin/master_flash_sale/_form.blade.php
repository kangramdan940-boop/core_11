<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror" value="{{ old('item_name', $item->item_name) }}" required>
        @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Harga Jual</label>
        <input type="number" step="0.01" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', $item->harga_jual) }}" required>
        @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tahun</label>
        <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $item->tahun) }}">
        @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Periode</label>
        <input type="text" name="periode" class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode', $item->periode) }}">
        @error('periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Modal</label>
        <input type="number" step="0.01" name="harga_modal" class="form-control @error('harga_modal') is-invalid @enderror" value="{{ old('harga_modal', $item->harga_modal) }}">
        @error('harga_modal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="mt-3">
    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill me-1"></i> Simpan</button>
</div>