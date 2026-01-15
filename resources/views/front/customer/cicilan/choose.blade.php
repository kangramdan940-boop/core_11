<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css')}}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css')}}" />
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png')}}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png')}}" />
    <title>Pilih Produk Cicilan || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<div class="header fixed-top">
    <div class="left">
        <a href="{{ route('customer.cicilan.index') }}" class="icon back-btn">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>
    </div>
    <h3>Pilih Produk Cicilan</h3>
</div>

<div class="app-content style-3">
    <div class="tf-container">

        @if (session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger py-2">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm mb-2">
            <div class="card-body p-3">
                <div class="h6 mb-2">{{ $item->layanan->nama_layanan }}</div>
                <div class="row g-3">
                    <div class="col-6"><div class="body-6 text-dark-4">Total Gram Dibuka</div><div class="h7 text-dark">{{ number_format((float)$item->total_gram_dibuka, 3) }} g</div></div>
                    <div class="col-6"><div class="body-6 text-dark-4">Jumlah Keping Dibuka</div><div class="h7 text-dark">{{ (int)$item->jumlah_keping_dibuka }}</div></div>
                    <div class="col-6"><div class="body-6 text-dark-4">Tenor Tersedia</div><div class="h7 text-dark">{{ (int)$item->layanan->tenor_min_bulan }}–{{ (int)$item->layanan->tenor_max_bulan }} bulan</div></div>
                    <div class="col-6"><div class="body-6 text-dark-4">DP (%)</div><div class="h7 text-dark">{{ number_format((float)$item->layanan->dp_min_persen, 2) }}%–{{ number_format((float)$item->layanan->dp_max_persen, 2) }}%</div></div>
                    <div class="col-6"><div class="body-6 text-dark-4">Harga per Gram (Akad)</div><div class="h7 text-dark">Rp {{ number_format((float) optional($item->latestAkad)->harga_per_gram_fix, 2) }}</div></div>
                    <div class="col-6"><div class="body-6 text-dark-4">Total Harga (Akad)</div><div class="h7 text-dark">Rp {{ number_format((float) ($item->total_gram_dibuka * (float) optional($item->latestAkad)->harga_per_gram_fix), 2) }}</div></div>
                </div>
            </div>
        </div>
 

        @php
            $pdfUrl = null;
            if (optional($item->latestAkad)->file_pdf_url) {
                $pdfUrl = \Illuminate\Support\Str::startsWith($item->latestAkad->file_pdf_url, ['http://','https://'])
                    ? $item->latestAkad->file_pdf_url
                    : asset($item->latestAkad->file_pdf_url);
            }
        @endphp
        <div class="card shadow-sm mb-2">
            <div class="card-body p-3">
                <div class="h7 text-dark mb-2">Informasi Agen & Rekening</div>
                <div class="row g-3">
                    <div class="col-6"><div class="body-6 text-dark-4">Nama Agen</div><div class="h7 text-dark">{{ optional($item->agen)->name ?? '-' }}</div></div>
                    <div class="col-6"><div class="body-6 text-dark-4">WhatsApp</div><div class="h7 text-dark">{{ optional($item->agen)->phone_wa ?? '-' }}</div></div>
                    <div class="col-12"><div class="body-6 text-dark-4">Nomor Rekening Agen</div><div class="h7 text-dark d-flex align-items-center gap-2"><span class="js-copy-target">{{ optional($item->agen)->rekening_nomor ?? '-' }}</span>@if(optional($item->agen)->rekening_nomor)<span class="js-copy" data-copy="{{ optional($item->agen)->rekening_nomor }}" title="Salin" aria-label="Salin" style="cursor:pointer;display:inline-flex;align-items:center;"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 1.5A1.5 1.5 0 0 1 6.5 0h5A1.5 1.5 0 0 1 13 1.5v9A1.5 1.5 0 0 1 11.5 12h-5A1.5 1.5 0 0 1 5 10.5v-9z" stroke="#6c757d" stroke-width="1"/><path d="M3 4h6c.55 0 1 .45 1 1v8c0 .55-.45 1-1 1H3c-.55 0-1-.45-1-1V5c0-.55.45-1 1-1z" stroke="#6c757d" stroke-width="1"/></svg></span><span class="text-success small js-copy-feedback d-none">Disalin</span>@endif</div></div>
                    @php $pay = \App\Models\MasterPaymentSetting::first(); @endphp
                    <div class="col-6"><div class="body-6 text-dark-4">Bank</div><div class="h7 text-dark">{{ $pay->bank_nama ?? 'BCA' }}</div></div>
                    <div class="col-6"><div class="body-6 text-dark-4">Atas Nama</div><div class="h7 text-dark">{{ optional($item->agen)->name ?? '-' }}</div></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-2">
            <div class="card-body p-3">
                <div class="h7 text-dark mb-2">Akad Murabahah (PDF)</div>
                @if ($pdfUrl)
                    <div class="ratio ratio-4x3">
                        <iframe src="{{ $pdfUrl }}#toolbar=1&navpanes=0&scrollbar=1" title="PDF Preview" style="width:100%;height:100%;" frameborder="0"></iframe>
                    </div>
                    <div class="mt-2">
                        <a class="btn-app button-1" href="{{ $pdfUrl }}" target="_blank" rel="noopener">Buka PDF</a>
                    </div>
                @else
                    <div class="body-6 text-dark-4">Belum ada dokumen PDF terlampir.</div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="h7 text-dark mb-2">Form Kontrak Cicilan</div>
                @php
                    $kepingTerpakai = (int) ($item->jumlah_keping_terpakai ?? 0);
                    $kepingSisa = max(0, (int)$item->jumlah_keping_dibuka - $kepingTerpakai);
                @endphp
                <form id="contractForm" action="{{ route('customer.cicilan.store-record', ['record' => encrypt((string)$item->id)]) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Tenor (bulan)</label>
                        <div class="input-group">
                            <input type="number" name="tenor_bulan" min="{{ (int)$item->layanan->tenor_min_bulan }}" max="{{ (int)$item->layanan->tenor_max_bulan }}" step="1" value="{{ old('tenor_bulan', (int)$item->layanan->tenor_min_bulan) }}" class="form-control @error('tenor_bulan') is-invalid @enderror" required>
                            <span class="input-group-text">bulan</span>
                        </div>
                        <small class="text-muted">Rentang: {{ (int)$item->layanan->tenor_min_bulan }}–{{ (int)$item->layanan->tenor_max_bulan }} bulan</small>
                        @error('tenor_bulan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">DP (%)</label>
                        <div class="input-group">
                            @php $selectedDp = (float) old('dp_persen', 12); @endphp
                            <select name="dp_persen" class="form-select @error('dp_persen') is-invalid @enderror" required>
                                @foreach ([5,10,20] as $v)
                                    <option value="{{ $v }}" {{ (float)$selectedDp === (float)$v ? 'selected' : '' }}>{{ number_format((float)$v, 2) }}%</option>
                                @endforeach
                            </select><span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Rentang: {{ number_format((float)$item->layanan->dp_min_persen, 2) }}%–{{ number_format((float)$item->layanan->dp_max_persen, 2) }}%</small>
                        <small class="text-muted d-block">Perkiraan DP dibayar: <span id="dpAmountInfo">Rp {{ number_format((float) (((old('jumlah_keping_diambil', 1) * (float) optional($item->gramasi)->gramasi) * (float) optional($item->latestAkad)->harga_per_gram_fix) * ($selectedDp) / 100), 2) }}</span></small>
                        @error('dp_persen')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nominal DP (Rp)</label>
                        <div class="input-group">
                            <input type="text" id="dpAmountInput" class="form-control" value="Rp 0" readonly>
                            <span class="input-group-text">IDR</span>
                        </div>
                        <small class="text-muted">Jumlah yang harus ditransfer. Kode Unik Transfer: <span id="uniqueCodeDisplay">000</span></small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Jumlah Keping Diambil</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_keping_diambil" min="1" max="{{ $kepingSisa }}" step="1" value="{{ old('jumlah_keping_diambil', 1) }}" class="form-control @error('jumlah_keping_diambil') is-invalid @enderror" required>
                            <span class="input-group-text">keping</span>
                        </div>
                        <small class="text-muted">Maksimal: {{ $kepingSisa }} keping</small>
                        @error('jumlah_keping_diambil')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bukti Pembayaran DP (opsional)</label>
                        <input type="file" name="file_bukti_bayar_dp" accept="image/*,application/pdf" class="form-control @error('file_bukti_bayar_dp') is-invalid @enderror">
                        <small class="text-muted">Format: JPG/PNG/PDF, maks 5 MB</small>
                        @error('file_bukti_bayar_dp')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                Saya telah membaca dan menyetujui Akad Murabahah dan syarat layanan.
                            </label>
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" id="submitBtn" class="btn-app button-1 flex-fill">Buat Kontrak Cicilan</button>
                        <button type="button" class="btn button-1 btn-outline-primary flex-fill" onclick="window.location.href='{{ route('customer.cicilan.index') }}'">Batal</button>
                    </div>
                    <div id="submitOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center bg-dark bg-opacity-25" style="z-index:9999;">
                        <div class="bg-white rounded p-3 text-center">
                            <div class="spinner-border text-warning" role="status"></div>
                            <div class="mt-2">Memproses, mohon tunggu...</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@include('front.customer.partials.menubar-footer', ['active' => 'produk'])
<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
<script>
const hargaPerGram = parseFloat('{{ (float) optional($item->latestAkad)->harga_per_gram_fix }}') || 0;
const gramPerKeping = parseFloat('{{ (float) optional($item->gramasi)->gramasi }}') || 0;
function formatRupiah(n){return 'Rp ' + (n||0).toLocaleString('id-ID',{minimumFractionDigits:2,maximumFractionDigits:2});}
function calcUniqueCode(jumlah, dpPercent){const base=(jumlah*37)+Math.round(dpPercent*10);return (base % 900) + 100;}
function updateDpInfo(){const dpField=document.querySelector('[name="dp_persen"]');const kepingInput=document.querySelector('input[name="jumlah_keping_diambil"]');const dpPercent=parseFloat(dpField?.value)||0;let jumlah=parseInt(kepingInput?.value)||0;const maxVal=parseInt(kepingInput?.max)||0;const minVal=parseInt(kepingInput?.min)||1;if(kepingInput){if(maxVal>0&&jumlah>maxVal){jumlah=maxVal;kepingInput.value=String(maxVal);}if(jumlah<minVal){jumlah=minVal;kepingInput.value=String(minVal);}}const gramasi=jumlah*gramPerKeping;const total=gramasi*hargaPerGram;const dpAmount=total*dpPercent/100;const baseInt=Math.floor(dpAmount);const uniq=calcUniqueCode(jumlah, dpPercent);const dpTotal=baseInt+uniq;const inf=document.getElementById('dpAmountInfo');if(inf)inf.textContent=formatRupiah(dpTotal);const inputAmt=document.getElementById('dpAmountInput');if(inputAmt)inputAmt.value=formatRupiah(dpTotal);const uniqEl=document.getElementById('uniqueCodeDisplay');if(uniqEl)uniqEl.textContent=String(uniq).padStart(3,'0');}document.addEventListener('DOMContentLoaded',()=>{updateDpInfo();['input','change'].forEach(evt=>{document.querySelector('[name="dp_persen"]')?.addEventListener(evt,updateDpInfo);document.querySelector('input[name="jumlah_keping_diambil"]')?.addEventListener(evt,updateDpInfo);});});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('contractForm');
  const btn = document.getElementById('submitBtn');
  const overlay = document.getElementById('submitOverlay');
  if(form){
    form.addEventListener('submit', function(e){
      if(form.dataset.submitting === '1'){ e.preventDefault(); return; }
      const inp = form.querySelector('input[name="jumlah_keping_diambil"]');
      let v = parseInt(inp?.value)||0; const maxVal = parseInt(inp?.max)||0; const minVal = parseInt(inp?.min)||1;
      if(inp){ if(maxVal>0 && v>maxVal){ inp.value = String(maxVal); v = maxVal; } if(v<minVal){ inp.value = String(minVal); v = minVal; } }
      if(maxVal>0 && v>maxVal){ e.preventDefault(); return; }
      form.dataset.submitting = '1';
      if(btn){ btn.setAttribute('disabled','disabled'); btn.classList.add('disabled'); }
      if(overlay){ overlay.classList.remove('d-none'); overlay.classList.add('d-flex'); }
    });
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  document.addEventListener('click', function(e){
    var btn = e.target.closest('.js-copy'); if(!btn) return;
    var text = btn.getAttribute('data-copy') || ''; if(!text) return;
    var showOk = function(){ var fb = btn.parentElement.querySelector('.js-copy-feedback'); if(fb){ fb.classList.remove('d-none'); setTimeout(function(){ fb.classList.add('d-none'); }, 1500); }};
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(showOk).catch(function(){ showOk(); });
    } else {
      var ta = document.createElement('textarea'); ta.value = text; document.body.appendChild(ta); ta.select(); try { document.execCommand('copy'); } catch(_) {} document.body.removeChild(ta); showOk();
    }
  });
});
</script>
</body>
</html>