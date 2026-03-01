@extends('layouts.admin')

@section('title', 'Detail Payment Log - Admin')
@section('page_title', 'Detail Payment Log')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>ID</strong><br>{{ $log->id }}</div>
                <div class="col-md-3"><strong>Kode Payment</strong><br>{{ $log->kode_payment }}</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($log->status) }}</div>
                <div class="col-md-3"><strong>Amount</strong><br>{{ number_format((float)$log->amount, 2, ',', '.') }} {{ $log->currency }}</div>

                <div class="col-md-3"><strong>Ref Type</strong><br>{{ strtoupper($log->ref_type) }}</div>
                <div class="col-md-3"><strong>Ref ID</strong><br>{{ $log->ref_id ?? '-' }}</div>
                <div class="col-md-3"><strong>Method</strong><br>{{ $log->payment_method ?? '-' }}</div>
                <div class="col-md-3"><strong>Provider</strong><br>{{ $log->provider ?? '-' }}</div>

                <div class="col-md-3"><strong>Channel</strong><br>{{ $log->payment_channel ?? '-' }}</div>
                <div class="col-md-3"><strong>Paid At</strong><br>{{ optional($log->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Failed At</strong><br>{{ optional($log->failed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Expired At</strong><br>{{ optional($log->expired_at)->format('Y-m-d H:i') ?? '-' }}</div>

                <div class="col-md-3"><strong>Refunded At</strong><br>{{ optional($log->refunded_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Dibuat</strong><br>{{ optional($log->created_at)->format('Y-m-d H:i') }}</div>
                <div class="col-md-3"><strong>Diupdate</strong><br>{{ optional($log->updated_at)->format('Y-m-d H:i') }}</div>
                <div class="col-md-12"><strong>Error Message</strong><br>{{ $log->error_message ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Request Payload</h6>
            <pre class="small mb-3" style="white-space:pre-wrap">{{ $log->request_payload ?? '-' }}</pre>
            <h6 class="mb-3">Response Payload</h6>
            <pre class="small" style="white-space:pre-wrap">{{ $log->response_payload ?? '-' }}</pre>
        </div>
    </div>
@endsection