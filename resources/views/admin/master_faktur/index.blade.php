@extends('layouts.admin.master')

@section('title', 'Faktur Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Faktur Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.faktur.index'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Faktur Emas</span>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari invoice, customer, payment no">
                    <button class="btn btn-sm btn-outline-primary" type="submit">Cari</button>
                </form>
                <a href="{{ route('admin.master.faktur.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Butik</th>
                        <th>Metode</th>
                        <th>VA</th>
                        <th>No Pembayaran</th>
                        <th>Grand Total</th>
                        <th>Items</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $d)
                        <tr>
                            <td>{{ $d->invoice_number ?? '-' }}</td>
                            <td>{{ $d->date ? \Carbon\Carbon::parse($d->date)->format("Y-m-d") : ($d->date_raw ?? '-') }}</td>
                            <td>{{ $d->customer_name ?? '-' }}</td>
                            <td>{{ $d->boutique_code_name ?? '-' }}</td>
                            <td>{{ $d->payment_method ?? '-' }}</td>
                            <td>{{ $d->virtual_account ?? '-' }}</td>
                            <td>{{ $d->payment_no ?? '-' }}</td>
                            <td>Rp {{ number_format((int)($d->grand_total_idr ?? 0), 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ $d->items_count }}</span></td>
                            <td>
                                <a href="{{ route('admin.master.faktur.show', $d) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                <a href="{{ route('admin.master.faktur.edit', $d) }}" class="btn btn-sm btn-outline-secondary ms-1">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $documents->links() }}
        </div>
    </div>
@endsection