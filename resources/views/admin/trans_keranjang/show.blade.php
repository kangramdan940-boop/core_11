@extends('layouts.admin.master')

@section('title', 'Detail Keranjang - Admin')
@section('sub-title', 'Transaksi Keranjang')
@section('breadcrumbExtra', 'Detail Keranjang')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.keranjang.index'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.trans.keranjang.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    @if(($keranjang->status_order ?? '') === 'perlu_dibayar')
        <form action="{{ route('admin.trans.keranjang.approve-payment', $keranjang) }}" method="POST" onsubmit="return confirm('Setujui pembayaran keranjang ini dan ubah semua PO menjadi PAID?')">
            @csrf
            <button type="submit" class="btn btn-success">Approve Pembayaran</button>
        </form>
    @endif
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">Info Keranjang</h6>
        <div class="row g-3">
            <div class="col-12 col-md-4"><strong>Kode:</strong> {{ $keranjang->kode_keranjang }}</div>
            <div class="col-12 col-md-4"><strong>Status Order:</strong> {{ $keranjang->status_order }}</div>
            <div class="col-12 col-md-4"><strong>Status Kadaluarsa:</strong> {{ $keranjang->status_kadaluarsa }}</div>
            <div class="col-12 col-md-4"><strong>Kadaluarsa:</strong> {{ optional($keranjang->expires_at)->format('Y-m-d H:i') ?? '-' }}</div>
            <div class="col-12 col-md-4"><strong>Ongkir:</strong> {{ number_format((float)($keranjang->ongkos_kirim ?? 0), 2, ',', '.') }}</div>
            <div class="col-12 col-md-4"><strong>Catatan:</strong> {{ $keranjang->catatan ?? '-' }}</div>
            <div class="col-12 col-md-4"><strong>Nama Pengirim:</strong> {{ $keranjang->nama_pengirim ?? '-' }}</div>
            <div class="col-12 col-md-4"><strong>Nominal Transfer:</strong> {{ number_format((float)($keranjang->nominal_transfer ?? 0), 2, ',', '.') }}</div>
            <div class="col-12 col-md-4"><strong>Bukti Transfer:</strong>
                @if(!empty($keranjang->bukti_transfer_url))
                    <a href="{{ asset($keranjang->bukti_transfer_url) }}" target="_blank" class="text-decoration-underline">Lihat</a>
                @else
                    -
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="mb-3">PO dalam Keranjang</h6>
        <table class="table table-sm table-bordered align-middle">
            <thead>
                <tr>
                    <th>Kode PO</th>
                    <th>Customer</th>
                    <th>Gramasi (Qty)</th>
                    <th>Total Gram</th>
                    <th>Biaya Pengiriman</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pos as $p)
                    <tr>
                        <td>{{ $p->kode_po }}</td>
                        <td>{{ optional($p->customer)->full_name ?? '-' }}</td>
                        <td>{{ number_format((float)(optional(optional($p->produk)->gramasi)->gramasi ?? 0), 3, ',', '.') }} gr x ({{ (int)($p->qty ?? 0) }})</td>
                        <td>{{ number_format((float)($p->total_gram ?? 0), 3, ',', '.') }}</td>
                        <td>{{ number_format((float)($p->shipping_cost ?? 0), 2, ',', '.') }}</td>
                        <td>{{ number_format((float)($p->total_amount ?? 0), 2, ',', '.') }}</td>
                        <td>{{ strtoupper((string)($p->status ?? '-')) }}</td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection