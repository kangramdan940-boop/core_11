@extends('layouts.admin.master')

@section('title', 'Flash Sale Order - Admin')
@section('sub-title', 'Transaksi')
@section('breadcrumbExtra', 'Flash Sale Order')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.flash-sale-orders.index'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Flash Sale Order</h5>
        <a href="{{ route('admin.trans.flash-sale-orders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>Nama Pembeli</th>
                    <th>No Telepon</th>
                    <th>Barang Flash Sale</th>
                    <th>Banyak</th>
                    <th>Alamat Pengiriman</th>
                    <th>Bukti TF</th>
                    <th>Bukti Paket</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td>{{ $o->customer_name ?? '-' }}</td>
                        <td>{{ $o->phone ?? '-' }}</td>
                        <td>{{ optional($o->flashSale)->item_name ?? '-' }}</td>
                        <td>{{ is_null($o->qty) ? '-' : (int)$o->qty }}</td>
                        <td style="max-width:300px;">{{ $o->shipping_address ?? '-' }}</td>
                        <td>@if($o->payment_proof_url)<a href="{{ asset($o->payment_proof_url) }}" target="_blank">Lihat</a>@else-@endif</td>
                        <td>@if($o->package_proof_url)<a href="{{ asset($o->package_proof_url) }}" target="_blank">Lihat</a>@else-@endif</td>
                        <td>{{ optional($o->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.trans.flash-sale-orders.show', $o) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            <form action="{{ route('admin.trans.flash-sale-orders.destroy', $o) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection