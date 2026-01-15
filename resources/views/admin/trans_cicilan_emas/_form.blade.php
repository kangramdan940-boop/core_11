<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Master Layanan Cicilan</label>
        <select name="master_layanan_emas_cicilan_id" class="form-select @error('master_layanan_emas_cicilan_id') is-invalid @enderror" required>
            <option value="">-- Pilih Layanan --</option>
            @foreach($masters as $m)
                <option value="{{ $m->id }}" {{ (int)old('master_layanan_emas_cicilan_id', $record->master_layanan_emas_cicilan_id ?? 0) === (int)$m->id ? 'selected' : '' }}>
                    {{ $m->kode_layanan }} — {{ $m->nama_layanan }}
                </option>
            @endforeach
        </select>
        @error('master_layanan_emas_cicilan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Agen</label>
        <select name="master_agen_id" class="form-select @error('master_agen_id') is-invalid @enderror">
            <option value="">-- Pilih Agen (opsional) --</option>
            @foreach($agens as $a)
                <option value="{{ $a->id }}" {{ (int)old('master_agen_id', $record->master_agen_id ?? 0) === (int)$a->id ? 'selected' : '' }}>
                    {{ $a->name }}
                </option>
            @endforeach
        </select>
        @error('master_agen_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Gramasi Emas</label>
        <select name="master_gramasi_emas_id" class="form-select @error('master_gramasi_emas_id') is-invalid @enderror" required>
            <option value="">-- Pilih Gramasi --</option>
            @foreach($gramasis as $g)
                <option value="{{ $g->id }}" {{ (int)old('master_gramasi_emas_id', $record->master_gramasi_emas_id ?? 0) === (int)$g->id ? 'selected' : '' }}>
                    {{ $g->nama }} — {{ number_format((float)$g->gramasi, 0, ',', '.') }} g
                </option>
            @endforeach
        </select>
        @error('master_gramasi_emas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Banyak Dibuka (Keping)</label>
        <input type="number" name="jumlah_keping_dibuka" min="1" class="form-control @error('jumlah_keping_dibuka') is-invalid @enderror"
               value="{{ old('jumlah_keping_dibuka', $record->jumlah_keping_dibuka ?? 1) }}" required>
        @error('jumlah_keping_dibuka')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Total Gram Dibuka</label>
        <input type="number" step="0.001" min="0" name="total_gram_dibuka" class="form-control @error('total_gram_dibuka') is-invalid @enderror"
               value="{{ old('total_gram_dibuka', $record->total_gram_dibuka ?? 0) }}" required>
        @error('total_gram_dibuka')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>