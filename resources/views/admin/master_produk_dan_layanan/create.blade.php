@extends('layouts.admin')

@section('title', 'Tambah Produk & Layanan - Admin')
@section('page_title', 'Tambah Produk & Layanan')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.produk-layanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.master_produk_dan_layanan._form')
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.master.produk-layanan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection