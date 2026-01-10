@extends('layouts.admin.master')

@section('title', 'Laporan Keuangan - Admin')
@section('sub-title', 'Main')
@section('breadcrumbExtra', 'Laporan Keuangan')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.reports.index'))

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Sejak (paid_at)</label>
                <input type="date" name="since" value="{{ $filters['since'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai (paid_at)</label>
                <input type="date" name="until" value="{{ $filters['until'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3 align-self-end">
                <button class="btn btn-primary">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6 class="mb-3">Ringkasan TransPo per Status</h6>
        <div class="table-responsive">
            <table class="table table-sm table-striped align-middle">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th class="text-end">Jumlah Transaksi</th>
                        <th class="text-end">Total Uang</th>
                        <th class="text-end">Total Gram</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($po_status_rows as $row)
                        <tr>
                            <td>{{ $row->status }}</td>
                            <td class="text-end">{{ (int) ($row->total_trans ?? 0) }}</td>
                            <td class="text-end">Rp {{ number_format((float) ($row->total_uang ?? 0), 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format((float) ($row->total_gram ?? 0), 3, ',', '.') }} g</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-semibold">
                        <td>Total</td>
                        <td class="text-end">{{ (int) ($po_total_count ?? 0) }}</td>
                        <td class="text-end">Rp {{ number_format((float) ($po_total_amount ?? 0), 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format((float) ($po_total_gram ?? 0), 3, ',', '.') }} g</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">Total Penjualan PO</div>
                        <div class="fs-5 fw-semibold">Rp {{ number_format($po_total_amount, 0, ',', '.') }}</div>
                    </div>
                    <i class="ri-file-list-3-line fs-3 text-primary"></i>
                </div>
                <div class="mt-2 text-muted">Biaya Pengiriman: Rp {{ number_format($po_total_shipping, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">Total Penjualan Ready</div>
                        <div class="fs-5 fw-semibold">Rp {{ number_format($ready_total_amount, 0, ',', '.') }}</div>
                    </div>
                    <i class="ri-flashlight-line fs-3 text-success"></i>
                </div>
                <div class="mt-2 text-muted">Biaya Pengiriman: Rp {{ number_format($ready_total_shipping, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted">Nominal Transfer Keranjang</div>
                <div class="fs-5 fw-semibold">Rp {{ number_format($keranjang_transfer_total, 0, ',', '.') }}</div>
                <div class="mt-3 text-muted">Pending Pembayaran PO: {{ $pending_po_count }} • Ready: {{ $pending_ready_count }}</div>
            </div>
        </div>
    </div>
</div>
@endsection