<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <title>Flash Sale</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
<section class="boarding-sec">
    <div class="tf-container py-4">
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-left">
        <h3 class="title mb-1">Flash Sale</h3>
        @if(isset($qtyLimit))
            <div class="h5">Batas Banyak: {{ $qtyLimit ?? '-' }}</div>
            <div class="h4">
                <div class="row align-items-center">
                    <div class="col-10">Grand Total: <span id="grandTotalInfo">Rp -</span></div>
                    <div class="col-2 text-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="copyTotalBtn" title="Copy nominal"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M16 1H4C2.895 1 2 1.895 2 3V15" stroke="#121927" stroke-width="1.5"/><rect x="8" y="5" width="14" height="16" rx="2" stroke="#121927" stroke-width="1.5"/></svg></button>
                    </div>
                </div>
            </div>
        @endif
        <div class="mt-2">
            <div class="card card-body bg-light border-0 rounded">
                <div class="row align-items-center">
                    <div class="col-10 fw-semibold" id="bankInfoText">{{ $bankInfo ?? '1277883403 BNI M RAMDAN GUMELAR' }}</div>
                    <input type="hidden" id="bankInfoHidden" value="{{ $bankInfo ?? '1277883403 BNI M RAMDAN GUMELAR' }}">
                    <div class="col-2 text-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 d-flex justify-content-center align-items-center" id="copyRekBtn" title="Copy rekening"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M16 1H4C2.895 1 2 1.895 2 3V15" stroke="#121927" stroke-width="1.5"/><rect x="8" y="5" width="14" height="16" rx="2" stroke="#121927" stroke-width="1.5"/></svg></button>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($expiresAt) && $expiresAt)
            <div class="h6 text-muted" id="countdownWrap">Berakhir dalam: <span id="countdownText">-</span></div>
            <input type="hidden" id="expiryTs" value="{{ (int) $expiresAt->timestamp }}">
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <form id="fsSelectForm" action="{{ route('public.flash-sale.select.store', [$phone, $eenc, $qenc, $benc]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pay_code" value="{{ (int)($payCode ?? 0) }}">

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Barang Flash Sale</label>
                        <select name="flash_sale_id" id="flashSaleSelect" class="form-select @error('flash_sale_id') is-invalid @enderror" required>
                            <option value="">— Pilih —</option>
                            @foreach(($items ?? []) as $it)
                                <option value="{{ $it->id }}" data-price="{{ (float)$it->harga_jual }}" @selected(old('flash_sale_id') == $it->id)>
                                    {{ $it->item_name }} ({{ number_format((float)$it->harga_jual, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('flash_sale_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Nama Pembeli (opsional)</label>
                        <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}">
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Alamat Pengiriman (opsional)</label>
                        <textarea name="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Bukti TF (wajib)</label>
                        <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                        @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12"><hr class="my-3"></div>
                </div>

                <div class="mt-12">
                    <button type="submit" class="tf-btn primary d-block w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>
<script>
(function(){
    var sel = document.getElementById('flashSaleSelect');
    if (sel) {
        sel.addEventListener('change', function(){
            var opt = sel.options[sel.selectedIndex];
            var price = parseFloat(opt?.getAttribute('data-price') || '0');
            var qty = parseInt('{{ (int)($qtyLimit ?? 0) }}', 10) || 0;
            var payCode = parseInt('{{ (int)($payCode ?? 0) }}', 10) || 0;
            var base = Math.round(price * qty);
            var total = Math.floor(base / 1000) * 1000 + payCode;
            var fmt = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
            document.getElementById('grandTotalInfo').textContent = isNaN(total) ? 'Rp -' : fmt.format(total);
        });
    }
})();
</script>
<script>
(function(){
  var expEl=document.getElementById('expiryTs');
  if(!expEl) return;
  var end=parseInt(expEl.value,10)||0;
  var btn=document.querySelector('button[type="submit"]');
  function fmt(t){
    if(t<=0) return '00:00';
    var s=t%60; var m=Math.floor(t/60)%60; var h=Math.floor(t/3600);
    return (h>0? (String(h).padStart(2,'0')+':') : '') + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
  }
  function tick(){
    var now=Math.floor(Date.now()/1000);
    var rem=end-now;
    var el=document.getElementById('countdownText');
    if(el) el.textContent=fmt(rem);
    var wrap=document.getElementById('countdownWrap');
    if(wrap){ if(rem<=60){ wrap.classList.add('text-danger'); } else { wrap.classList.remove('text-danger'); } }
    if(rem<=0){ if(btn){ btn.disabled=true; btn.classList.add('disabled'); btn.textContent='Link Kadaluarsa'; } clearInterval(timer); }
  }
  tick();
  var timer=setInterval(tick,1000);
})();
</script>
<script>
(function(){
  function copyText(txt){
    if (navigator.clipboard && navigator.clipboard.writeText) { return navigator.clipboard.writeText(txt); }
    var ta=document.createElement('textarea'); ta.value=txt; document.body.appendChild(ta); ta.select(); try{ document.execCommand('copy'); }catch(e){} document.body.removeChild(ta); return Promise.resolve();
  }
  var copyBtn=document.getElementById('copyRekBtn');
  if(copyBtn){ copyBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); var raw=document.getElementById('bankInfoText')?.textContent||''; var only=(raw.match(/\d+/) || [''])[0]; copyText(only).then(function(){ alert('Copy berhasil'); }); }); }
  var copyTotal=document.getElementById('copyTotalBtn');
  if(copyTotal){ copyTotal.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); var txt=document.getElementById('grandTotalInfo')?.textContent||''; copyText(txt).then(function(){ alert('Copy berhasil'); }); }); }
  var form=document.getElementById('fsSelectForm');
  if(form){ form.addEventListener('submit', function(e){ var proof=form.querySelector('input[name="payment_proof"]'); var endEl=document.getElementById('expiryTs'); var end=parseInt(endEl?.value||'0',10)||0; var now=Math.floor(Date.now()/1000); var rem=Math.max(0,end-now); function fmt(t){ if(t<=0) return '00:00'; var s=t%60; var m=Math.floor(t/60)%60; var h=Math.floor(t/3600); return (h>0? (String(h).padStart(2,'0')+':') : '') + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0'); } if(!proof || !proof.files || !proof.files.length){ e.preventDefault(); e.stopPropagation(); if(window.Swal && Swal.fire){ Swal.fire({ icon:'warning', title:'Lengkapi Bukti TF', text:'Waktu kadaluarsa tersisa: '+fmt(rem) }); } else { alert('Bukti TF wajib diunggah. Waktu kadaluarsa tersisa: '+fmt(rem)); } return; } var payCodeEl=form.querySelector('input[name="pay_code"]'); var payCode=parseInt(payCodeEl?.value||'0',10)||0; var sel=document.getElementById('flashSaleSelect'); var opt=sel? sel.options[sel.selectedIndex] : null; var price=parseFloat(opt?.getAttribute('data-price')||'0'); var qty=parseInt('{{ (int)($qtyLimit ?? 0) }}',10)||0; var base=Math.round(price*qty); var total=Math.floor(base/1000)*1000 + payCode; var fmtId=new Intl.NumberFormat('id-ID',{ style:'currency', currency:'IDR', maximumFractionDigits:0 }); var el=document.getElementById('grandTotalInfo'); if(el) el.textContent=fmtId.format(total); e.preventDefault(); e.stopPropagation(); if(window.Swal && Swal.fire){ Swal.fire({ icon:'question', title:'Apakah Anda yakin akan submit?', showCancelButton:true, confirmButtonText:'Yes', cancelButtonText:'No' }).then(function(r){ if(r.isConfirmed){ form.submit(); } }); } else { if(confirm('Apakah Anda yakin akan submit?')){ form.submit(); } }
  }); }
})();
</script>
</body>
</html>