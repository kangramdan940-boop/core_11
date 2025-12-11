@extends('layouts.admin.master')

@section('title', 'Tambah Brand Emas - Admin')
@section('sub-title', 'Brand Emas')
@section('breadcrumbExtra', 'Tambah Brand Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.brand-emas.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Tambah Brand</h6>
            <a href="{{ route('admin.master.brand-emas.index') }}" class="btn btn-outline-secondary btn-sm px-2" style="height:28px;">← Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.master.brand-emas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.master_brand_emas._form', ['brand' => null])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.brand-emas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
