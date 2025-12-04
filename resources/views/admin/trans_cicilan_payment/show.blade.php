@extends('layouts.admin')

@section('title', 'Detail Pembayaran Cicilan - Admin')
@section('page_title', 'Detail Pembayaran Cicilan')

@section('content')
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.trans.cicilan-payments.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
        <a href="{{ route('admin.trans.cicilan.show', $payment->kontrak) }}" class="btn btn-sm btn-outline-secondary">Lihat Kontrak</a>
        <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-sm btn-outline-secondary">Payment Logs</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan Pembayaran</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>ID</strong><br>{{ $payment->id }}</div>
                <div class="col-md-3"><strong>Kontrak</strong><br>{{ optional($payment->kontrak)->kode_kontrak ?? '-' }}</div>
                <div class="col-md-3"><strong>Cicilan Ke</strong><br>{{ $payment->cicilan_ke }}</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($payment->status) }}</div>

                <div class="col-md-3"><strong>Due Date</strong><br>{{ optional($payment->due_date)->format('Y-m-d') ?? '-' }}</div>
                <div class="col-md-3"><strong>Amount Due</strong><br>{{ number_format((float)$payment->amount_due, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Amount Paid</strong><br>{{ number_format((float)$payment->amount_paid, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Paid At</strong><br>{{ optional($payment->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>

                <div class="col-md-3"><strong>Metode</strong><br>{{ $payment->payment_method ?? '-' }}</div>
                <div class="col-md-3"><strong>Ref</strong><br>{{ $payment->payment_reference ?? '-' }}</div>
                <div class="col-md-12"><strong>Catatan</strong><br>{{ $payment->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Payment Logs Terkait</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode Payment</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Metode</th>
                            <th>Provider</th>
                            <th>Paid At</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paymentLogs as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->kode_payment }}</td>
                                <td>{{ strtoupper($l->status) }}</td>
                                <td>{{ number_format((float)$l->amount, 2, ',', '.') }} {{ $l->currency }}</td>
                                <td>{{ $l->payment_method ?? '-' }}</td>
                                <td>{{ $l->provider ?? '-' }}</td>
                                <td>{{ optional($l->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.trans.payment-logs.show', $l) }}"
                                       class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">Belum ada payment log terkait.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection