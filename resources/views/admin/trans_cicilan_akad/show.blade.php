@extends('layouts.admin.master')

@section('title', 'Detail Akad Murabahah - Admin')
@section('sub-title', 'Akad Murabahah')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan-akad.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Akad: {{ $akad->nomor_akad }}</h5>
            <div class="hstack gap-2">
                <a href="{{ route('admin.trans.cicilan-akad.edit', $akad) }}" class="btn btn-light-primary"><i class="ri-pencil-line"></i> Edit</a>
                <a href="{{ route('admin.trans.cicilan-akad.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-2">
                <div class="col-md-3">
                    <div class="small text-muted">Nomor Akad</div>
                    <div class="fw-semibold">{{ $akad->nomor_akad }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Tanggal Akad</div>
                    <div class="fw-semibold">{{ $akad->tanggal_akad ? $akad->tanggal_akad->format('d/m/Y') : '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Status</div>
                    <div class="fw-semibold">{{ ucfirst(str_replace('_',' ', $akad->status)) }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Cicilan Emas / Kontrak</div>
                    <div class="fw-semibold">
                        @if($akad->kontrak)
                            #{{ $akad->kontrak->id }}
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>

            <hr>

            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <div class="small text-muted">Agen</div>
                    <div class="fw-semibold">{{ optional($akad->agen)->name ?? '-' }}</div>
                    <div class="text-muted">{{ optional($akad->agen)->address_line ?? '' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Pihak Penjual</div>
                    <div class="fw-semibold">{{ ucfirst($akad->pihak_penjual_type) }}</div>
                    <div class="text-muted">{{ $akad->penjual_nama ?? '' }}</div>
                    <div class="text-muted">{{ $akad->penjual_alamat ?? '' }}</div>
                </div>
            </div>

            <hr>

            <div class="row g-3 mb-2">
                <div class="col-md-3">
                    <div class="small text-muted">Gramasi Total</div>
                    <div class="fw-semibold">{{ number_format((float)$akad->gramasi_total, 3, ',', '.') }} g</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Harga per Gram</div>
                    <div class="fw-semibold">
                        {{ $akad->harga_per_gram_fix !== null ? number_format((float)$akad->harga_per_gram_fix,2,',','.') : '-' }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Harga Total Kontrak</div>
                    <div class="fw-semibold">
                        {{ $akad->harga_total_kontrak !== null ? number_format((float)$akad->harga_total_kontrak,2,',','.') : '-' }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Tenor</div>
                    <div class="fw-semibold">{{ $akad->tenor_bulan !== null ? $akad->tenor_bulan . ' bulan' : '-' }}</div>
                </div>
            </div>

            <div class="row g-3 mb-2">
                <div class="col-md-3">
                    <div class="small text-muted">DP</div>
                    <div class="fw-semibold">
                        {{ $akad->dp_amount !== null ? number_format((float)$akad->dp_amount,2,',','.') : '-' }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Cicilan per Bulan</div>
                    <div class="fw-semibold">
                        {{ $akad->cicilan_per_bulan !== null ? number_format((float)$akad->cicilan_per_bulan,2,',','.') : '-' }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Margin %</div>
                    <div class="fw-semibold">
                        {{ $akad->margin_persen !== null ? number_format((float)$akad->margin_persen,2,',','.') . ' %' : '-' }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Total Margin</div>
                    <div class="fw-semibold">
                        {{ $akad->margin_amount_total !== null ? number_format((float)$akad->margin_amount_total,2,',','.') : '-' }}
                    </div>
                </div>
            </div>

            <hr>

            <div class="row g-3 mb-2">
                <div class="col-md-3">
                    <div class="small text-muted">Tanda Tangan Buyer</div>
                    <div class="fw-semibold">{{ $akad->buyer_signed_at ? $akad->buyer_signed_at->format('d/m/Y H:i') : '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Tanda Tangan Seller</div>
                    <div class="fw-semibold">{{ $akad->seller_signed_at ? $akad->seller_signed_at->format('d/m/Y H:i') : '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Buyer Signature URL</div>
                    <div class="fw-semibold">{{ $akad->buyer_signature_url ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Seller Signature URL</div>
                    <div class="fw-semibold">{{ $akad->seller_signature_url ?? '-' }}</div>
                </div>
            </div>

            <hr>

            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <div class="small text-muted">Dokumen Akad</div>
                    <div class="hstack gap-2">
                        @if($akad->file_pdf_url)
                            <a href="{{ asset($akad->file_pdf_url) }}" target="_blank" class="btn btn-sm btn-light-primary"><i class="ri-file-pdf-line"></i> Buka PDF</a>
                            <a href="#" class="btn btn-sm btn-light btn-preview-pdf" data-pdf-url="{{ asset($akad->file_pdf_url) }}"><i class="ri-eye-line"></i> Preview</a>
                        @else
                            <span class="text-muted">PDF belum diunggah</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Catatan</div>
                    <div>{{ $akad->catatan ?? '-' }}</div>
                </div>
            </div>

            <div class="row g-3 mb-2">
                <div class="col-md-12">
                    <div class="small text-muted">Syarat & Ketentuan</div>
                    <div>{{ $akad->syarat_ketentuan ?? '-' }}</div>
                </div>
            </div>

            @if(is_array($akad->pasal_ketentuan) && count($akad->pasal_ketentuan))
                <div class="row g-3 mb-2">
                    <div class="col-md-12">
                        <div class="small text-muted">Pasal Ketentuan</div>
                        <ul class="list-group list-group-flush">
                            @foreach($akad->pasal_ketentuan as $pasal)
                                <li class="list-group-item">{{ is_string($pasal) ? $pasal : json_encode($pasal) }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="pdfPreviewModal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <iframe id="pdfPreviewFrame" src="" style="border:0;width:100%;height:80vh;"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-preview-pdf').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-pdf-url');
            if (!url) return;
            const frame = document.getElementById('pdfPreviewFrame');
            if (frame) frame.src = url;
            const modalEl = document.getElementById('pdfPreviewModal');
            if (!modalEl) return;
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: false });
                modal.show();
            } else {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
            }
        });
    });
});
</script>
@endsection