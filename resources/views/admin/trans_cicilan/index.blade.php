@extends('layouts.admin.master')

@section('title', 'Cicilan Emas - Admin')
@section('sub-title', 'Transaksi Cicilan')
@section('breadcrumbExtra', 'Cicilan Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan.index'))

@section('content')
    <div class="card shadow-sm">
        <table id="cicilanTable" class="table table-hover align-middle table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
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
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>

    </div>
@endsection

@section('css')
@endsection

@section('js')
    <script>window._dpProofModalInit=function(){var m=document.getElementById('dpProofModal');if(!m){document.body.insertAdjacentHTML('beforeend','<div class="modal fade" id="dpProofModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content"><div class="modal-body p-0"><img id="dpProofImg" src="" class="w-100 d-none" alt="Bukti Transfer"><iframe id="dpProofPdf" src="" class="w-100 d-none" style="height:80vh;border:0;"></iframe></div></div></div></div>');}};document.addEventListener('DOMContentLoaded',function(){window._dpProofModalInit();document.addEventListener('click',function(e){var t=e.target.closest('.js-dp-proof');if(!t)return;var src=t.getAttribute('data-src');if(!src)return;var isPdf=/\.pdf($|\?)/i.test(src);var img=document.getElementById('dpProofImg');var pdf=document.getElementById('dpProofPdf');if(isPdf){img&&img.classList.add('d-none');pdf&&(pdf.classList.remove('d-none'),pdf.setAttribute('src',src));}else{pdf&&pdf.classList.add('d-none');img&&(img.classList.remove('d-none'),img.setAttribute('src',src));}var modal=new bootstrap.Modal(document.getElementById('dpProofModal'));modal.show();});});</script>


@endsection