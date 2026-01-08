@extends('layouts.admin.master')

@section('title', 'Tambah Stok Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Tambah Stok Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.gold-stocks.index'))

@section('content')
    <form action="{{ route('admin.master.gold-stocks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.master_gold_stock._form', ['stock' => null, 'mitras' => $mitras])
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.master.gold-stocks.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection