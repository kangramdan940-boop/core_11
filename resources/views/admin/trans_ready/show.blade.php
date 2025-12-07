@extends('layouts.admin')

@section('title', 'Detail Transaksi Emas Ready - Admin')
@section('page_title', 'Detail Transaksi Emas Ready')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trans.ready.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
            <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-sm btn-outline-secondary">Payment Logs</a>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.trans.ready.update-status', $ready) }}" method="POST" class="d-flex align-items-center gap-2">
                @csrf
                <select name="status" class="form-select form-select-sm" style="min-width:180px;">
                    <option value="pending_payment" @selected($ready->status==='pending_payment')>PENDING_PAYMENT</option>
                    <option value="paid" @selected($ready->status==='paid')>PAID</option>
                    <option value="shipped" @selected($ready->status==='shipped')>SHIPPED</option>
                    <option value="completed" @selected($ready->status==='completed')>COMPLETED</option>
                    <option value="cancelled" @selected($ready->status==='cancelled')>CANCELLED</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
 
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan Transaksi</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode Trans</strong><br>{{ $ready->kode_trans }}</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($ready->status) }}</div>
                <div class="col-md-3"><strong>Qty</strong><br>{{ $ready->qty }}</div>
                <div class="col-md-3"><strong>Total Amount</strong><br>{{ number_format((float)$ready->total_amount, 2, ',', '.') }}</div>

                <div class="col-md-3"><strong>Customer</strong><br>{{ optional($ready->customer)->full_name ?? '-' }}</div>
                <div class="col-md-3"><strong>Agen</strong><br>{{ optional($ready->agen)->name ?? '-' }}</div>
                <div class="col-md-3"><strong>Item</strong><br>{{ optional($ready->readyStock)->kode_item ?? '-' }}</div>
                <div class="col-md-3"><strong>Metode Bayar</strong><br>{{ $ready->payment_method ?? '-' }}</div>

                <div class="col-md-3"><strong>Ordered At</strong><br>{{ optional($ready->ordered_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Paid At</strong><br>{{ optional($ready->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Shipped At</strong><br>{{ optional($ready->shipped_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Completed At</strong><br>{{ optional($ready->completed_at)->format('Y-m-d H:i') ?? '-' }}</div>

                <div class="col-md-12"><strong>Catatan</strong><br>{{ $ready->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Data Pengiriman</h6>
            <div class="row g-3">
                <div class="col-md-6"><strong>Nama</strong><br>{{ $ready->shipping_name ?? '-' }}</div>
                <div class="col-md-6"><strong>WhatsApp</strong><br>{{ $ready->shipping_phone ?? '-' }}</div>
                <div class="col-md-12"><strong>Alamat</strong><br>{{ $ready->shipping_address ?? '-' }}</div>
                <div class="col-md-4"><strong>Kota</strong><br>{{ $ready->shipping_city ?? '-' }}</div>
                <div class="col-md-4"><strong>Provinsi</strong><br>{{ $ready->shipping_province ?? '-' }}</div>
                <div class="col-md-4"><strong>Kode Pos</strong><br>{{ $ready->shipping_postal_code ?? '-' }}</div>
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
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <a href="{{ route('admin.trans.payment-logs.show', $l) }}" class="btn btn-sm btn-outline-primary py-0 px-2">Detail</a>
                                        @if ($l->payment_method === 'manual_transfer' && $l->status === 'pending')
                                        <form action="{{ route('admin.trans.payment-logs.approve', $l) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success py-0 px-2">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.trans.payment-logs.reject', $l) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger py-0 px-2">Reject</button>
                                        </form>
                                        @endif
                                    </div>
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

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Log Status Transaksi</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ strtoupper($log->status) }}</td>
                                <td>{{ $log->description ?? '-' }}</td>
                                <td>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Belum ada log status.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection