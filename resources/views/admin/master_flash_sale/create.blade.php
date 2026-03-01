@extends('layouts.admin.master')

@section('title', 'Tambah Flash Sale - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Flash Sale')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.flash-sales.index'))

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.master.flash-sales.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.master.flash-sales.store') }}" method="POST">
                @csrf
                @include('admin.master_flash_sale._form', ['item' => $item])
            </form>
        </div>
    </div>
@endsection