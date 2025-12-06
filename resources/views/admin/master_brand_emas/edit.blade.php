@extends('layouts.admin.master')

@section('title', 'Edit Brand Emas - Admin')
@section('sub-title', 'Brand Emas')
@section('breadcrumbExtra', 'Edit Brand Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.brand-emas.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.brand-emas.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.master_brand_emas._form', ['brand' => $brand])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.brand-emas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
