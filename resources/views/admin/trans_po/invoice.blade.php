@extends('layouts.admin.master')

@section('title', 'Invoice Transaksi PO - Admin')
@section('sub-title', 'Transaksi PO')
@section('breadcrumbExtra', 'Invoice')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.po.index'))

@section('content')
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.trans.po.show', $po) }}" class="btn btn-secondary">← Kembali</a>
        <a href="{{ route('admin.trans.po.invoice.pdf', $po) }}" class="btn btn-outline-primary">Unduh PDF</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="h4 mb-1">Invoice Transaksi</div>
                    <div class="small text-muted">Kode Pesanan: {{ $po->kode_po ?? ('PO-' . $po->id) }}</div>
                    <div class="small text-muted">Tanggal: {{ \Carbon\Carbon::parse($po->created_at)->format('d M Y H:i') }}</div>
                </div>
                <img src="{{ asset('front/images/logo/logo-light.png') }}" alt="Jajanemas" style="height:30px;">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <div class="fw-semibold mb-2">Ditagihkan Kepada</div>
                        <div>{{ optional($po->customer)->full_name ?? '-' }}</div>
                        <div class="small text-muted">WA: {{ optional($po->customer)->phone_wa ?? '-' }}</div>
                        <div class="small text-muted">Email: {{ optional($po->customer)->email ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <div class="fw-semibold mb-2">Status & Pembayaran</div>
                        <div>Status: {{ strtoupper($po->status) }}</div>
                        <div>Metode Bayar: {{ $po->payment_method ?? '-' }}</div>
                        <div>Referensi: {{ $po->payment_reference ?? '-' }}</div>
                        <div>Dibayar: {{ optional($po->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Berat/Unit (g)</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $qty = (int) ($po->qty ?? 0);
                            $gram = (float) ($po->total_gram ?? 0);
                            $hargaPerKeping = (float) ($po->harga_per_keping ?? 0);
                            $jasaPerUnit = (float) (optional($po->produk)->harga_jasa ?? 0);
                            $subtotal = ($hargaPerKeping * $qty) + ($jasaPerUnit * $qty);
                        @endphp
                        <tr>
                            <td>Pre-Order Emas</td>
                            <td class="text-end">{{ $qty }}</td>
                            <td class="text-end">{{ number_format($gram, 3, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        @php $shippingCost = (float) ($po->shipping_cost ?? 0); @endphp
                        <tr>
                            <th colspan="3" class="text-end">Biaya Pengiriman</th>
                            <th class="text-end">{{ number_format($shippingCost, 2, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Total Bayar</th>
                            <th class="text-end">Rp {{ number_format((float) ($po->total_amount ?? 0), 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if(($po->delivery_type ?? '') === 'shipping')
                <div class="mt-3">
                    <div class="fw-semibold mb-1">Data Pengiriman</div>
                    <div>{{ $po->shipping_name ?? '-' }} · {{ $po->shipping_phone ?? '-' }}</div>
                    <div>{{ $po->shipping_address ?? '-' }}</div>
                    <div>{{ $po->shipping_city ?? '-' }}, {{ $po->shipping_province ?? '-' }} {{ $po->shipping_postal_code ?? '' }}</div>
                </div>
            @endif

            @if(($paymentLogs ?? collect())->count() > 0)
                <div class="mt-3">
                    <div class="fw-semibold mb-1">Riwayat Pembayaran</div>
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>Kode</th><th>Status</th><th class="text-end">Jumlah</th><th>Metode</th><th>Paid At</th></tr>
                        </thead>
                        <tbody>
                            @foreach($paymentLogs as $pl)
                                <tr>
                                    <td>{{ $pl->kode_payment }}</td>
                                    <td>{{ strtoupper($pl->status) }}</td>
                                    <td class="text-end">{{ number_format((float)$pl->amount, 2, ',', '.') }} {{ $pl->currency }}</td>
                                    <td>{{ $pl->payment_method ?? '-' }}</td>
                                    <td>{{ optional($pl->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection