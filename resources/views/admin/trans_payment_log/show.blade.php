@extends('layouts.admin.master')

@section('title', 'Detail Payment Log - Admin')
@section('sub-title', 'Payment Logs')
@section('breadcrumbExtra', 'Detail Pembayaran')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.payment-logs.index'))

@section('content')
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-secondary">← Kembali</a>
        @php
            $relatedRoute = null;
            if ($log->ref_type === 'po') {
                $relatedRoute = route('admin.trans.po.show', $log->ref_id);
            } elseif ($log->ref_type === 'ready') {
                $relatedRoute = route('admin.trans.ready.show', $log->ref_id);
            } elseif ($log->ref_type === 'cicilan') {
                $relatedRoute = route('admin.trans.cicilan.show', $log->ref_id);
            } elseif ($log->ref_type === 'cicilan_payment') {
                $relatedRoute = route('admin.trans.cicilan-payments.show', $log->ref_id);
            }
        @endphp
        @if ($relatedRoute)
            <a href="{{ $relatedRoute }}" class="btn btn-outline-primary">Lihat Transaksi Terkait</a>
        @endif
        @if ($log->payment_method === 'manual_transfer' && $log->status === 'pending')
            <form action="{{ route('admin.trans.payment-logs.approve', $log) }}" method="POST" class="d-inline ms-2">
                @csrf
                <button type="submit" class="approve-btn btn btn-success w-100"><i class="bi bi-check2-circle me-1"></i> Approve</button>
            </form>
            <form action="{{ route('admin.trans.payment-logs.reject', $log) }}" method="POST" class="d-inline ms-1">
                @csrf
                <button type="submit" class="reject-btn btn btn-outline-danger w-100"><i class="bi bi-x-circle me-1"></i> Reject</button>
            </form>
        @endif
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Ringkasan Pembayaran</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Kode</strong>
                    <div class="mt-1">{{ $log->kode_payment }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Status</strong>
                    <div class="mt-1">{{ strtoupper($log->status) }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Jumlah</strong>
                    <div class="mt-1">{{ number_format((float)$log->amount, 2, ',', '.') }} {{ $log->currency }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Metode</strong>
                    <div class="mt-1">{{ $log->payment_method ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Provider</strong>
                    <div class="mt-1">{{ $log->provider ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Channel</strong>
                    <div class="mt-1">{{ $log->payment_channel ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Ref Type</strong>
                    <div class="mt-1">{{ $log->ref_type }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Ref ID</strong>
                    <div class="mt-1">{{ $log->ref_id ?? '-' }}</div>
                </div>

                <div class="col-md-3">
                    <strong>Dibuat</strong>
                    <div class="mt-1">{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Dibayar</strong>
                    <div class="mt-1">{{ optional($log->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Gagal</strong>
                    <div class="mt-1">{{ optional($log->failed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Kedaluwarsa</strong>
                    <div class="mt-1">{{ optional($log->expired_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    @php
        $payloadReq = $log->request_payload ? json_decode($log->request_payload, true) : null;
    @endphp

    @if ($log->payment_method === 'manual_transfer')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3 fs-5"># Konfirmasi Manual Transfer</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        @if ($payloadReq && !empty($payloadReq['proof_path']))
                            <img src="{{ asset('storage/' . $payloadReq['proof_path']) }}"
                                 alt="Bukti Transfer"
                                 class="img-fluid rounded border">
                        @else
                            <div class="text-muted">Tidak ada bukti transfer</div>
                        @endif
                    </div>
                    <div class="col-md-8 small">
                        <div class="mb-2">
                            <strong>Nama Pengirim</strong>
                            <div class="mt-1">{{ $payloadReq['sender_name'] ?? '-' }}</div>
                        </div>
                        <div class="mb-2">
                            <strong>Nominal</strong>
                            <div class="mt-1">{{ number_format((float)$log->amount, 2, ',', '.') }} {{ $log->currency }}</div>
                        </div>
                        <div class="mb-2">
                            <strong>Dikirim</strong>
                            <div class="mt-1">{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                        </div>
                        <div class="mb-2">
                            <strong>Status</strong>
                            <div class="mt-1">{{ strtoupper($log->status) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Payload</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Request Payload</strong>
                    <pre class="mt-3 small bg-light p-2 border rounded mb-0">{{ $log->request_payload ?? '-' }}</pre>
                </div>
                <div class="col-md-6">
                    <strong>Response Payload</strong>
                    <pre class="mt-3 small bg-light p-2 border rounded mb-0">{{ $log->response_payload ?? '-' }}</pre>
                </div>
                @if ($log->error_message)
                    <div class="col-12">
                        <strong>Error Message</strong>
                        <pre class="mt-3 small bg-light p-2 border rounded mb-0 text-danger">{{ $log->error_message }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.approve-btn').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Konfirmasi Approve',
                        text: 'Apakah Anda yakin menyetujui pembayaran ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Approve',
                        cancelButtonText: 'Batal'
                    }).then(function(result){ if (result.isConfirmed && form) form.submit(); });
                });
            });
            document.querySelectorAll('.reject-btn').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Konfirmasi Reject',
                        text: 'Apakah Anda yakin menolak pembayaran ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Reject',
                        cancelButtonText: 'Batal'
                    }).then(function(result){ if (result.isConfirmed && form) form.submit(); });
                });
            });
        });
    </script>
@endsection