<div class="row g-3">

    @if(!empty($detailed))
    <div class="col-md-4">
        <label class="form-label">Agen</label>
        <select id="agenSelectAkad" name="master_agen_id" class="form-select @error('master_agen_id') is-invalid @enderror" required>
            <option value="">-- Pilih Agen --</option>
            @foreach($agens as $a)
                <option value="{{ $a->id }}" {{ (int)old('master_agen_id', $akad->master_agen_id ?? 0) === (int)$a->id ? 'selected' : '' }}>
                    {{ $a->name }}
                </option>
            @endforeach
        </select>
        @error('master_agen_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif

    <div class="col-md-4">
        <label class="form-label">Cicilan Emas</label>
        <select id="cicilanEmasSelectAkad" name="trans_cicilan_emas_id" class="form-select @error('trans_cicilan_emas_id') is-invalid @enderror" required>
            <option value="">-- Pilih Cicilan Emas --</option>
            @foreach($records as $r)
                <option value="{{ $r->id }}" {{ (int)old('trans_cicilan_emas_id', $akad->trans_cicilan_emas_id ?? 0) === (int)$r->id ? 'selected' : '' }}>
                    #{{ $r->id }} — {{ optional($r->layanan)->nama_layanan }} — {{ optional($r->gramasi)->nama }} ({{ number_format((float)optional($r->gramasi)->gramasi,0,',','.') }} g) — {{ number_format((float)$r->total_gram_dibuka,3,',','.') }} g
                </option>
            @endforeach
        </select>
        @error('trans_cicilan_emas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Nomor Akad</label>
        <input type="text" name="nomor_akad" maxlength="50"
               value="{{ old('nomor_akad', $akad->nomor_akad ?? '') }}"
               class="form-control @error('nomor_akad') is-invalid @enderror" required>
        @error('nomor_akad')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if(!empty($detailed))
    <div class="col-md-3">
        <label class="form-label">Tanggal Akad</label>
        <input type="date" name="tanggal_akad"
               value="{{ old('tanggal_akad', isset($akad->tanggal_akad) ? $akad->tanggal_akad->toDateString() : now()->toDateString()) }}"
               class="form-control @error('tanggal_akad') is-invalid @enderror">
        @error('tanggal_akad')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        @php
            $statuses = ['draft','signed_buyer','signed_seller','signed_both','active','cancelled'];
        @endphp
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="">-- Pilih Status --</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" {{ old('status', $akad->status ?? '') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $s)) }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Upload File PDF Akad</label>
        <input type="file" name="file_pdf" accept="application/pdf"
               class="form-control @error('file_pdf') is-invalid @enderror">
        @error('file_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif

    <div class="col-md-3">
        <label class="form-label">Gramasi Total (g)</label>
        <input type="number" step="0.001" min="0" name="gramasi_total"
               value="{{ old('gramasi_total', isset($akad->gramasi_total) ? (float)$akad->gramasi_total : '') }}"
               class="form-control @error('gramasi_total') is-invalid @enderror">
        @error('gramasi_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga per Gram</label>
        <input type="number" step="0.01" min="0" name="harga_per_gram_fix"
               value="{{ old('harga_per_gram_fix', isset($akad->harga_per_gram_fix) ? (float)$akad->harga_per_gram_fix : '') }}"
               class="form-control @error('harga_per_gram_fix') is-invalid @enderror">
        @error('harga_per_gram_fix')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Harga Total Kontrak</label>
        <input type="number" step="0.01" min="0" name="harga_total_kontrak"
               value="{{ old('harga_total_kontrak', isset($akad->harga_total_kontrak) ? (float)$akad->harga_total_kontrak : '') }}"
               class="form-control @error('harga_total_kontrak') is-invalid @enderror">
        @error('harga_total_kontrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Tenor (bulan)</label>
        <input type="number" step="1" min="1" name="tenor_bulan"
               value="{{ old('tenor_bulan', isset($akad->tenor_bulan) ? (int)$akad->tenor_bulan : '') }}"
               class="form-control @error('tenor_bulan') is-invalid @enderror">
        @error('tenor_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">DP</label>
        <input type="number" step="0.01" min="0" name="dp_amount"
               value="{{ old('dp_amount', isset($akad->dp_amount) ? (float)$akad->dp_amount : '') }}"
               class="form-control @error('dp_amount') is-invalid @enderror">
        @error('dp_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Cicilan per Bulan</label>
        <input type="number" step="0.01" min="0" name="cicilan_per_bulan"
               value="{{ old('cicilan_per_bulan', isset($akad->cicilan_per_bulan) ? (float)$akad->cicilan_per_bulan : '') }}"
               class="form-control @error('cicilan_per_bulan') is-invalid @enderror">
        @error('cicilan_per_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Margin %</label>
        <input type="number" step="0.01" min="0" name="margin_persen"
               value="{{ old('margin_persen', isset($akad->margin_persen) ? (float)$akad->margin_persen : '') }}"
               class="form-control @error('margin_persen') is-invalid @enderror">
        @error('margin_persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Total Margin</label>
        <input type="number" step="0.01" min="0" name="margin_amount_total"
               value="{{ old('margin_amount_total', isset($akad->margin_amount_total) ? (float)$akad->margin_amount_total : '') }}"
               class="form-control @error('margin_amount_total') is-invalid @enderror">
        @error('margin_amount_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Tanda Tangan Buyer</label>
        <input type="datetime-local" name="buyer_signed_at"
               value="{{ old('buyer_signed_at', isset($akad->buyer_signed_at) ? $akad->buyer_signed_at->format('Y-m-d\TH:i') : '') }}"
               class="form-control @error('buyer_signed_at') is-invalid @enderror">
        @error('buyer_signed_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Tanda Tangan Seller</label>
        <input type="datetime-local" name="seller_signed_at"
               value="{{ old('seller_signed_at', isset($akad->seller_signed_at) ? $akad->seller_signed_at->format('Y-m-d\TH:i') : '') }}"
               class="form-control @error('seller_signed_at') is-invalid @enderror">
        @error('seller_signed_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Buyer Signature URL</label>
        <input type="text" name="buyer_signature_url" maxlength="255"
               value="{{ old('buyer_signature_url', $akad->buyer_signature_url ?? '') }}"
               class="form-control @error('buyer_signature_url') is-invalid @enderror">
        @error('buyer_signature_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Seller Signature URL</label>
        <input type="text" name="seller_signature_url" maxlength="255"
               value="{{ old('seller_signature_url', $akad->seller_signature_url ?? '') }}"
               class="form-control @error('seller_signature_url') is-invalid @enderror">
        @error('seller_signature_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if(!empty($detailed))
    <div class="col-md-12">
        <label class="form-label">Syarat & Ketentuan</label>
        <textarea name="syarat_ketentuan" rows="4" class="form-control @error('syarat_ketentuan') is-invalid @enderror">{{ old('syarat_ketentuan', $akad->syarat_ketentuan ?? '') }}</textarea>
        @error('syarat_ketentuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif

    @if(!empty($detailed))
    <div class="col-md-12">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $akad->catatan ?? '') }}</textarea>
        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif
</div>