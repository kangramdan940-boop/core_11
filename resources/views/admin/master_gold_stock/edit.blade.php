@extends('layouts.admin.master')

@section('title', 'Edit Stok Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Edit Stok Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.gold-stocks.index'))

@section('content')
    <form action="{{ route('admin.master.gold-stocks.update', $stock) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.master_gold_stock._form', ['stock' => $stock, 'mitras' => $mitras])
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.master.gold-stocks.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection