@extends('layouts.admin.master')

@section('title', 'Edit Flash Sale - Admin')
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
            <form action="{{ route('admin.master.flash-sales.update', $item) }}" method="POST">
                @csrf @method('PUT')
                @include('admin.master_flash_sale._form', ['item' => $item])
            </form>
        </div>
    </div>
@endsection