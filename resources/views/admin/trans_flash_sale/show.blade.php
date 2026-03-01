@extends('layouts.admin.master')

@section('title', 'Detail Flash Sale Order - Admin')
@section('sub-title', 'Transaksi')
@section('breadcrumbExtra', 'Flash Sale Order')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.flash-sale-orders.index'))

@section('content')
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.trans.flash-sale-orders.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Kode Order</dt><dd class="col-sm-9">{{ $order->kode_order }}</dd>
                <dt class="col-sm-3">Barang Flash Sale</dt><dd class="col-sm-9">{{ $order->flashSale->item_name ?? '-' }}</dd>
                <dt class="col-sm-3">Harga</dt><dd class="col-sm-9">{{ number_format((float)$order->price, 2) }}</dd>
                <dt class="col-sm-3">Banyak</dt><dd class="col-sm-9">{{ (int)$order->qty }}</dd>
                <dt class="col-sm-3">Nomor Telepon</dt><dd class="col-sm-9">{{ $order->phone }}</dd>
                <dt class="col-sm-3">Alamat Pengiriman</dt><dd class="col-sm-9">{{ $order->shipping_address }}</dd>
                <dt class="col-sm-3">Resi</dt><dd class="col-sm-9">{{ $order->resi ?? '-' }}</dd>
                <dt class="col-sm-3">Bukti Bayar</dt>
                <dd class="col-sm-9">@if($order->payment_proof_url)<a href="{{ asset($order->payment_proof_url) }}" target="_blank">Lihat</a>@else-@endif</dd>
                <dt class="col-sm-3">Bukti Paket</dt>
                <dd class="col-sm-9">@if($order->package_proof_url)<a href="{{ asset($order->package_proof_url) }}" target="_blank">Lihat</a>@else-@endif</dd>
                <dt class="col-sm-3">Dibuat</dt><dd class="col-sm-9">{{ optional($order->created_at)->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>
@endsection