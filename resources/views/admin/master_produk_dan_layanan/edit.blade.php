@extends('layouts.admin')

@section('title', 'Edit Produk & Layanan - Admin')
@section('page_title', 'Edit Produk & Layanan')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.produk-layanan.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.master_produk_dan_layanan._form', ['item' => $item])
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.master.produk-layanan.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection