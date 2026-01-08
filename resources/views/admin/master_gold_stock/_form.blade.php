<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Mitra</label>
        <select name="master_mitra_brankas_id" class="form-select">
            <option value="">-</option>
            @foreach($mitras as $m)
                <option value="{{ $m->id }}" @if(old('master_mitra_brankas_id', $stock->master_mitra_brankas_id ?? '') == $m->id) selected @endif>{{ $m->nama_lengkap }} ({{ $m->kode_mitra }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Gramasi (g)</label>
        <input type="number" step="0.001" min="0" name="gramasi" class="form-control" value="{{ old('gramasi', $stock->gramasi ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Qty (keping)</label>
        <input type="number" step="1" min="0" name="qty" class="form-control" value="{{ old('qty', $stock->qty ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">No Faktur</label>
        <input type="text" name="no_faktur" class="form-control" value="{{ old('no_faktur', $stock->no_faktur ?? '') }}">
    </div>
    <div class="col-md-12">
        <label class="form-label">Uraian</label>
        <textarea name="uraian" rows="3" class="form-control">{{ old('uraian', $stock->uraian ?? '') }}</textarea>
    </div>
    <div class="col-md-3">
        <label class="form-label">Berat (g total)</label>
        <input type="number" step="0.001" min="0" name="berat" class="form-control" value="{{ old('berat', $stock->berat ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Harga</label>
        <input type="number" step="0.01" min="0" name="harga" class="form-control" value="{{ old('harga', $stock->harga ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Total Pembayaran</label>
        <input type="number" step="0.01" min="0" name="total_pembayaran" class="form-control" value="{{ old('total_pembayaran', $stock->total_pembayaran ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">File Faktur</label>
        <input type="file" name="file_faktur" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        @if(isset($stock) && !empty($stock->file_faktur_url))
            @php($raw = $stock->file_faktur_url)
            @php($href = (substr($raw,0,7)==='http://' || substr($raw,0,8)==='https://' || substr($raw,0,1)==='/') ? $raw : asset(ltrim($raw, '/')))
            @php($ext = strtolower(pathinfo(parse_url($raw, PHP_URL_PATH) ?? $raw, PATHINFO_EXTENSION)))
            <div class="mt-2">
                <div class="small text-muted mb-1">Preview Faktur</div>
                @if(in_array($ext, ['jpg','jpeg','png','gif','webp','svg']))
                    <a href="{{ $href }}" target="_blank" rel="noopener">
                        <img src="{{ $href }}" alt="Faktur" style="max-height:120px;object-fit:contain;border:1px solid #dee2e6;border-radius:4px;">
                    </a>
                @elseif($ext === 'pdf')
                    <div class="border rounded" style="height:320px;overflow:hidden;">
                        <embed src="{{ $href }}" type="application/pdf" style="width:100%;height:100%;" />
                    </div>
                    <div class="mt-2">
                        <a href="{{ $href }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Buka PDF</a>
                    </div>
                @else
                    <a href="{{ $href }}" target="_blank" rel="noopener">Buka File</a>
                @endif
            </div>
        @endif
    </div>

    <div class="col-md-3">
        <label class="form-label">Struk Komisi</label>
        <input type="file" name="struk_komisi" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        @if(isset($stock) && !empty($stock->struk_komisi_url))
            @php($raw = $stock->struk_komisi_url)
            @php($href = (substr($raw,0,7)==='http://' || substr($raw,0,8)==='https://' || substr($raw,0,1)==='/') ? $raw : asset(ltrim($raw, '/')))
            @php($ext = strtolower(pathinfo(parse_url($raw, PHP_URL_PATH) ?? $raw, PATHINFO_EXTENSION)))
            <div class="mt-2">
                <div class="small text-muted mb-1">Preview Struk Komisi</div>
                @if(in_array($ext, ['jpg','jpeg','png','gif','webp','svg']))
                    <a href="{{ $href }}" target="_blank" rel="noopener">
                        <img src="{{ $href }}" alt="Struk Komisi" style="max-height:120px;object-fit:contain;border:1px solid #dee2e6;border-radius:4px;">
                    </a>
                @elseif($ext === 'pdf')
                    <div class="border rounded" style="height:320px;overflow:hidden;">
                        <embed src="{{ $href }}" type="application/pdf" style="width:100%;height:100%;" />
                    </div>
                    <div class="mt-2">
                        <a href="{{ $href }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Buka PDF</a>
                    </div>
                @else
                    <a href="{{ $href }}" target="_blank" rel="noopener">Buka File</a>
                @endif
            </div>
        @endif
    </div>

    <div class="col-md-3">
        <label class="form-label">Struk Bayar ke Mitra</label>
        <input type="file" name="struk_bayar_mitra" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        @if(isset($stock) && !empty($stock->struk_bayar_mitra_url))
            @php($raw = $stock->struk_bayar_mitra_url)
            @php($href = (substr($raw,0,7)==='http://' || substr($raw,0,8)==='https://' || substr($raw,0,1)==='/') ? $raw : asset(ltrim($raw, '/')))
            @php($ext = strtolower(pathinfo(parse_url($raw, PHP_URL_PATH) ?? $raw, PATHINFO_EXTENSION)))
            <div class="mt-2">
                <div class="small text-muted mb-1">Preview Struk Bayar</div>
                @if(in_array($ext, ['jpg','jpeg','png','gif','webp','svg']))
                    <a href="{{ $href }}" target="_blank" rel="noopener">
                        <img src="{{ $href }}" alt="Struk Bayar" style="max-height:120px;object-fit:contain;border:1px solid #dee2e6;border-radius:4px;">
                    </a>
                @elseif($ext === 'pdf')
                    <div class="border rounded" style="height:320px;overflow:hidden;">
                        <embed src="{{ $href }}" type="application/pdf" style="width:100%;height:100%;" />
                    </div>
                    <div class="mt-2">
                        <a href="{{ $href }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Buka PDF</a>
                    </div>
                @else
                    <a href="{{ $href }}" target="_blank" rel="noopener">Buka File</a>
                @endif
            </div>
        @endif
    </div>

    <div class="col-md-3">
        <label class="form-label">Status Pengambilan</label>
        <select name="status_pengambilan" class="form-select">
            @php($st = old('status_pengambilan', $stock->status_pengambilan ?? 'belum_diambil'))
            <option value="belum_diambil" @if($st==='belum_diambil') selected @endif>Belum Diambil</option>
            <option value="sudah_diambil" @if($st==='sudah_diambil') selected @endif>Sudah Diambil</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Uang Modal Mitra</label>
        <input type="number" step="0.01" min="0" name="uang_modal_mitra" class="form-control" value="{{ old('uang_modal_mitra', $stock->uang_modal_mitra ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Uang Ganti oleh Jajan Emas</label>
        <input type="number" step="0.01" min="0" name="uang_ganti_jajan_emas" class="form-control" value="{{ old('uang_ganti_jajan_emas', $stock->uang_ganti_jajan_emas ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Uang Komisi Mitra</label>
        <input type="number" step="0.01" min="0" name="uang_komisi_mitra" class="form-control" value="{{ old('uang_komisi_mitra', $stock->uang_komisi_mitra ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Total Komisi</label>
        <input type="number" step="0.01" min="0" name="total_komisi" class="form-control" value="{{ old('total_komisi', $stock->total_komisi ?? '') }}">
    </div>
</div>