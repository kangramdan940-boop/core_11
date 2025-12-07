@extends('layouts.admin')

@section('title', 'Detail Payment Log - Admin')
@section('page_title', 'Detail Pembayaran')

@section('content')
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
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
            <a href="{{ $relatedRoute }}" class="btn btn-sm btn-outline-primary">Lihat Transaksi Terkait</a>
        @endif
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan Pembayaran</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode</strong><br>{{ $log->kode_payment }}</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($log->status) }}</div>
                <div class="col-md-3"><strong>Jumlah</strong><br>{{ number_format((float)$log->amount, 2, ',', '.') }} {{ $log->currency }}</div>
                <div class="col-md-3"><strong>Metode</strong><br>{{ $log->payment_method ?? '-' }}</div>

                <div class="col-md-3"><strong>Provider</strong><br>{{ $log->provider ?? '-' }}</div>
                <div class="col-md-3"><strong>Channel</strong><br>{{ $log->payment_channel ?? '-' }}</div>
                <div class="col-md-3"><strong>Ref Type</strong><br>{{ $log->ref_type }}</div>
                <div class="col-md-3"><strong>Ref ID</strong><br>{{ $log->ref_id ?? '-' }}</div>

                <div class="col-md-3"><strong>Dibuat</strong><br>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Dibayar</strong><br>{{ optional($log->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Gagal</strong><br>{{ optional($log->failed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Kedaluwarsa</strong><br>{{ optional($log->expired_at)->format('Y-m-d H:i') ?? '-' }}</div>
            </div>
        </div>
    </div>

    @php
        $payloadReq = $log->request_payload ? json_decode($log->request_payload, true) : null;
    @endphp

    @if ($log->payment_method === 'manual_transfer')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Konfirmasi Manual Transfer</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        @if ($payloadReq && !empty($payloadReq['proof_path']))
                            <img src="{{ asset($payloadReq['proof_path']) }}"
                                 alt="Bukti Transfer"
                                 class="img-fluid rounded border">
                        @else
                            <div class="text-muted">Tidak ada bukti transfer</div>
                        @endif
                    </div>
                    <div class="col-md-8 small">
                        <div><strong>Nama Pengirim</strong><br>{{ $payloadReq['sender_name'] ?? '-' }}</div>
                        <div><strong>Nominal</strong><br>{{ number_format((float)$log->amount, 2, ',', '.') }} {{ $log->currency }}</div>
                        <div><strong>Dikirim</strong><br>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                        <div><strong>Status</strong><br>{{ strtoupper($log->status) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Payload</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Request Payload</strong>
                    <pre class="small bg-light p-2 border rounded mb-0">{{ $log->request_payload ?? '-' }}</pre>
                </div>
                <div class="col-md-6">
                    <strong>Response Payload</strong>
                    <pre class="small bg-light p-2 border rounded mb-0">{{ $log->response_payload ?? '-' }}</pre>
                </div>
                @if ($log->error_message)
                    <div class="col-12">
                        <strong>Error Message</strong>
                        <pre class="small bg-light p-2 border rounded mb-0 text-danger">{{ $log->error_message }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection