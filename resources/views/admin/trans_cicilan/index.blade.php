@extends('layouts.admin.master')

@section('title', 'Cicilan Emas - Admin')
@section('sub-title', 'Transaksi Cicilan')
@section('breadcrumbExtra', 'Cicilan Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan.index'))

@section('content')
    <div class="d-flex justify-content-end mb-2">
        <form id="cancelWaitingDpForm" action="{{ route('admin.trans.cicilan.cancel-waiting-dp') }}" method="POST" class="d-inline">
            @csrf
            <button type="button" id="cancelWaitingDpBtn" class="btn btn-outline-danger btn-sm">Batalkan Semua 'Menunggu DP'</button>
        </form>
    </div>
    <ul class="nav nav-pills mb-2">
        <li class="nav-item"><a href="{{ route('admin.trans.cicilan.index') }}" class="nav-link {{ request('status') ? '' : 'active' }}">Semua</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.cicilan.index', ['status' => 'active']) }}" class="nav-link {{ request('status') === 'active' ? 'active' : '' }}">Active</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.cicilan.index', ['status' => 'canceled']) }}" class="nav-link {{ in_array(request('status'), ['canceled','cancelled'], true) ? 'active' : '' }}">Cancelled</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.cicilan.index', ['status' => 'menunggu-dp']) }}" class="nav-link {{ in_array(request('status'), ['menunggu-dp','menunggu dp'], true) ? 'active' : '' }}">Menunggu DP</a></li>
    </ul>
    <form action="{{ route('admin.trans.cicilan.index') }}" method="GET" class="mb-2 d-flex gap-2">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="customer" class="form-control" placeholder="Filter Customer" value="{{ request('customer') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.trans.cicilan.index', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-light">Reset</a>
    </form>
    @if (request('status') === 'active')
        @php
            $totalGramasi = $contracts->sum(function($c){ return (float) ($c->gramasi ?? 0); });
            $totalPcs = $contracts->sum(function($c){ return (int) ($c->jumlah_keping_diambil ?? 0); });
        @endphp
        <div class="row g-2 mb-2">
            <div class="col-md-6">
                @if (request('status') === 'active')
        @php
            $totalGramasi = $contracts->sum(function($c){ return (float) ($c->gramasi ?? 0); });
            $totalPcs = $contracts->sum(function($c){ return (int) ($c->jumlah_keping_diambil ?? 0); });
            $totalNilaiKontrak = $contracts->sum(function($c){ return (float) ($c->harga_total_kontrak ?? 0); });
            $totalDpActive = $contracts->sum(function($c){ return (float) ($c->dp_amount ?? 0); });
        @endphp
        <div class="row g-2 mb-2">
            <div class="col-md-4">
                <div class="card shadow-sm"><div class="card-body py-2"><div class="text-muted small">Total Gramasi</div><div class="h5 mb-0">{{ number_format((float)$totalGramasi, 3, ',', '.') }} gr</div></div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm"><div class="card-body py-2"><div class="text-muted small">Total Qty (pcs)</div><div class="h5 mb-0">{{ number_format((int)$totalPcs, 0, ',', '.') }}</div></div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm"><div class="card-body py-2"><div class="text-muted small">Total Nilai Kontrak (IDR)</div><div class="h5 mb-0">{{ number_format((float)$totalNilaiKontrak, 2, ',', '.') }}</div></div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm"><div class="card-body py-2"><div class="text-muted small">Total DP (IDR)</div><div class="h5 mb-0">{{ number_format((float)$totalDpActive, 2, ',', '.') }}</div></div></div>
            </div>
        </div>
    @endif
                <div class="card shadow-sm">
                    <div class="card-body py-2">
                        <div class="text-muted small">Total Gramasi</div>
                        <div class="h5 mb-0">{{ number_format((float)$totalGramasi, 3, ',', '.') }} gr</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body py-2">
                        <div class="text-muted small">Total Qty (pcs)</div>
                        <div class="h5 mb-0">{{ number_format((int)$totalPcs, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card shadow-sm">
        <table id="cicilanTable" class="table table-hover align-middle table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th>NO</th>
                    <th>ID</th>
                    <th>Kode Kontrak</th>
                    <th>Customer</th>
                    <th>Agen</th>
                    <th>DP (IDR)</th>
                    <th>Tenor</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contracts as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->kode_kontrak }}</td>
                        <td>{{ optional($c->customer)->full_name ?? '-' }}</td>
                        <td>{{ optional($c->agen)->name ?? '-' }}</td>
                        <td>{{ number_format((float)$c->dp_amount, 2, ',', '.') }}</td>
                        <td>{{ $c->tenor_bulan }} bln</td>
                        <td>{{ strtoupper($c->status) }}</td>
                        <td>{{ optional($c->created_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.trans.cicilan.show', $c) }}" class="btn icon-btn-sm btn-light-primary"><i class="bi bi-eye"></i></a>
                                @if ($c->file_bukti_bayar_dp)
                                    <button type="button" class="btn icon-btn-sm btn-light-secondary js-dp-proof" data-src="{{ asset($c->file_bukti_bayar_dp) }}" title="Preview Bukti Transfer"><i class="bi bi-card-image"></i></button>
                                @endif
                                <button type="button" class="btn icon-btn-sm btn-light-warning js-dp-upload" data-id="{{ $c->id }}" title="Upload/Update Bukti DP"><i class="bi bi-upload"></i></button>
                                <button type="button" class="btn icon-btn-sm btn-light-info js-cicilan-list" data-id="{{ $c->id }}" title="Tagihan Cicilan"><i class="bi bi-list-ul"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>

    </div>
<script>(function(){var csrf='{{ csrf_token() }}';var baseUpload='{{ route('admin.trans.cicilan-payments.index') }}';window._cicilanPaymentsModalInit=function(){var m=document.getElementById('cicilanPaymentsModal');if(!m){document.body.insertAdjacentHTML('beforeend','<div class="modal fade" id="cicilanPaymentsModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content"><div class="modal-header"><h6 class="modal-title">Tagihan Cicilan</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="cicilanPaymentsBody"></div></div></div></div></div>');}};function d(s){try{return new Date(s).toISOString().slice(0,10);}catch(e){return s||'-';}}function a(n){var x=parseFloat(n||0);return x.toLocaleString('id-ID',{minimumFractionDigits:2,maximumFractionDigits:2});}document.addEventListener('DOMContentLoaded',function(){window._cicilanPaymentsModalInit();document.addEventListener('click',function(e){var t=e.target.closest('.js-cicilan-list');if(t){var id=t.getAttribute('data-id');if(!id)return;fetch('/admin/trans/cicilan/'+id+'/payments/data',{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(function(r){return r.json()}).then(function(json){var items=(json&&json.data)||[];var html='<div class="table-responsive"><table class="table table-hover align-middle table-nowrap w-100"><thead><tr><th>#</th><th>Due Date</th><th>Amount Due</th><th>Status</th><th>Upload Bukti</th></tr></thead><tbody>';for(var i=0;i<items.length;i++){var p=items[i];html+='<tr><td>'+(p.cicilan_ke||'-')+'</td><td>'+d(p.due_date)+'</td><td>'+a(p.amount_due)+'</td><td>'+(String(p.status||'').toUpperCase())+'</td><td>'+(String(p.status)==='pending'?('<form method="POST" enctype="multipart/form-data" action="'+baseUpload+'/'+p.id+'/confirm-payment"><input type="hidden" name="_token" value="'+csrf+'"><div class="row g-2 align-items-center"><div class="col-12 col-md"><input type="number" step="0.01" name="nominal_transfer" class="form-control form-control-sm" placeholder="Nominal" required></div><div class="col-12 col-md"><input type="text" name="nama_pengirim" class="form-control form-control-sm" placeholder="Nama Pengirim" required></div><div class="col-12 col-md"><input type="file" name="bukti_transfer" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.webp" required></div><div class="col-auto"><button type="submit" class="btn btn-sm btn-primary">Upload</button></div></div></form>'):'-')+'</td></tr>';};html+='</tbody></table></div>';var body=document.getElementById('cicilanPaymentsBody');if(body)body.innerHTML=html;var m=document.getElementById('cicilanPaymentsModal');if(m&&window.bootstrap){new bootstrap.Modal(m).show();}});}});});})();</script>
@endsection

@section('css')
@endsection

@section('js')
    <script>window._dpProofModalInit=function(){var m=document.getElementById('dpProofModal');if(!m){document.body.insertAdjacentHTML('beforeend','<div class="modal fade" id="dpProofModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content"><div class="modal-body p-0"><img id="dpProofImg" src="" class="w-100 d-none" alt="Bukti Transfer"><iframe id="dpProofPdf" src="" class="w-100 d-none" style="height:80vh;border:0;"></iframe></div></div></div></div>');}};window._dpUploadModalInit=function(){var m=document.getElementById('dpUploadModal');if(!m){document.body.insertAdjacentHTML('beforeend','<div class="modal fade" id="dpUploadModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form id="dpUploadForm" method="POST" enctype="multipart/form-data" action=""><div class="modal-header"><h6 class="modal-title">Upload Bukti DP</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="mb-3"><label class="form-label">File Bukti (jpg, jpeg, png, webp, pdf)</label><input type="file" name="bukti_dp" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf" required></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Upload</button></div></form></div></div></div>');}};document.addEventListener('DOMContentLoaded',function(){window._dpProofModalInit();window._dpUploadModalInit();document.addEventListener('click',function(e){var t=e.target.closest('.js-dp-proof');if(t){var src=t.getAttribute('data-src');if(!src)return;var isPdf=/\.pdf($|\?)/i.test(src);var img=document.getElementById('dpProofImg');var pdf=document.getElementById('dpProofPdf');if(isPdf){img&&img.classList.add('d-none');pdf&&(pdf.classList.remove('d-none'),pdf.setAttribute('src',src));}else{pdf&&pdf.classList.add('d-none');img&&(img.classList.remove('d-none'),img.setAttribute('src',src));}var modal=new bootstrap.Modal(document.getElementById('dpProofModal'));modal.show();return;}var u=e.target.closest('.js-dp-upload');if(u){var id=u.getAttribute('data-id');if(!id)return;var form=document.getElementById('dpUploadForm');var base='{{ route('admin.trans.cicilan.dp-proof', 0) }}';if(form){form.setAttribute('action',base.replace(/\/0\/dp-proof$/, '/'+id+'/dp-proof'));}var modal=new bootstrap.Modal(document.getElementById('dpUploadModal'));modal.show();}});var btn=document.getElementById('cancelWaitingDpBtn');if(btn){btn.addEventListener('click',function(){var submit=function(){var f=document.getElementById('cancelWaitingDpForm');if(f)f.submit();};if(typeof Swal!=='undefined'){Swal.fire({title:'Konfirmasi',text:'Yakin ingin membatalkan semua kontrak status MENUNGGU DP?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, batalkan',cancelButtonText:'Batal'}).then(function(r){if(r.isConfirmed)submit();});}else{if(confirm('Yakin ingin membatalkan semua kontrak status MENUNGGU DP?'))submit();}});}});</script>


@endsection