<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css')}}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css')}}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png')}}" />
    <title>Flash Sale</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <h5 class="mb-0">Flash Sale: {{ $flashSale->item_name }}</h5>
        <div class="text-muted">Harga Jual: {{ number_format((float)$flashSale->harga_jual, 2) }}</div>
        @if($flashSale->tahun || $flashSale->periode)
            <div class="text-muted">Period: {{ $flashSale->tahun ?? '-' }} {{ $flashSale->periode ?? '' }}</div>
        @endif
        @if(isset($qtyLimit))
            <div class="h5">Batas Banyak: {{ $qtyLimit ?? '-' }}</div>
            @php $grand = (((float)$flashSale->harga_jual) * (int)($qtyLimit ?? 0)) + (int)($payCode ?? 0); @endphp
            <div class="alert alert-info">
                <div class="row align-items-center">
                    <div class="col-10">Grand Total: <span id="grandTotalInfo">Rp {{ number_format($grand, 0, ',', '.') }}</span></div>
                    <div class="col-2 text-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 d-flex justify-content-center align-items-center" id="copyTotalBtn" title="Copy nominal"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M16 1H4C2.895 1 2 1.895 2 3V15" stroke="#121927" stroke-width="1.5"/><rect x="8" y="5" width="14" height="16" rx="2" stroke="#121927" stroke-width="1.5"/></svg></button>
                    </div>
                </div>
                <div id="copyTotalAlert" class="alert alert-success py-1 px-2 mt-2 d-none">Nominal berhasil disalin</div>
            </div>
        @endif
        @if(isset($expiresAt) && $expiresAt)
            <div class="h6" id="countdownWrap">Berakhir dalam: <span id="countdownText">-</span></div>
            <input type="hidden" id="expiryTs" value="{{ (int) $expiresAt->timestamp }}">
        @endif
    </div>

    <div class="mt-2">
        <div class="alert alert-info">
            <div class="row align-items-center">
                <div class="col-10">No. Rekening: <strong id="bankNumber">1277883403</strong> — <strong>BNI</strong> — <strong>M RAMDAN GUMELAR</strong></div>
                <div class="col-2 text-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100 d-flex justify-content-center align-items-center" id="copyRekBtn" title="Copy rekening"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M16 1H4C2.895 1 2 1.895 2 3V15" stroke="#121927" stroke-width="1.5"/><rect x="8" y="5" width="14" height="16" rx="2" stroke="#121927" stroke-width="1.5"/></svg></button>
                </div>
            </div>
            <div id="copyRekAlert" class="alert alert-success py-1 px-2 mt-2 d-none">Nomor rekening berhasil disalin</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('public.flash-sale.store', [$flashSale->id, $enc, $phone, $eenc, $qenc]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pay_code" value="{{ (int)($payCode ?? 0) }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" value="{{ $phone }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Pembeli (opsional)</label>
                        <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}">
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Alamat Pengiriman (opsional)</label>
                        <textarea name="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Bukti TF (opsional)</label>
                        <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                        @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12"><hr class="my-3"></div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
<script>
(function(){
  var expEl=document.getElementById('expiryTs');
  if(expEl){
    var end=parseInt(expEl.value,10)||0;
    var btn=document.querySelector('button[type=\"submit\"]');
    function fmt(t){ if(t<=0) return '00:00'; var s=t%60; var m=Math.floor(t/60)%60; var h=Math.floor(t/3600); return (h>0? (String(h).padStart(2,'0')+':') : '') + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0'); }
    function tick(){ var now=Math.floor(Date.now()/1000); var rem=end-now; var el=document.getElementById('countdownText'); if(el) el.textContent=fmt(rem); var wrap=document.getElementById('countdownWrap'); if(wrap){ if(rem<=60){ wrap.classList.add('text-danger'); } else { wrap.classList.remove('text-danger'); } } if(rem<=0){ if(btn){ btn.disabled=true; btn.classList.add('disabled'); btn.textContent='Link Kadaluarsa'; } clearInterval(timer); } }
    tick(); var timer=setInterval(tick,1000);
  }
  function copyText(txt){
    if (navigator.clipboard && navigator.clipboard.writeText) { return navigator.clipboard.writeText(txt); }
    var ta=document.createElement('textarea'); ta.value=txt; document.body.appendChild(ta); ta.select(); try{ document.execCommand('copy'); }catch(e){} document.body.removeChild(ta); return Promise.resolve();
  }
  function showAlert(id){ var el=document.getElementById(id); if(!el) return; el.classList.remove('d-none'); setTimeout(function(){ el.classList.add('d-none'); }, 1500); }
  var copyBtn=document.getElementById('copyRekBtn');
  if(copyBtn){ copyBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); var raw=document.getElementById('bankNumber')?.textContent||''; var only=raw.replace(/\D+/g,''); copyText(only).then(function(){ showAlert('copyRekAlert'); }); }); }
  var copyTotal=document.getElementById('copyTotalBtn');
  if(copyTotal){ copyTotal.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); var txt=document.getElementById('grandTotalInfo')?.textContent||''; copyText(txt).then(function(){ showAlert('copyTotalAlert'); }); }); }
})();
</script>
</body>
</html>