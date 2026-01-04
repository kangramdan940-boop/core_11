<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Tambah Asset</h6>
        <a href="{{ route('admin.master.assets.index') }}" class="btn btn-outline-secondary btn-sm px-2" style="height:28px;">← Kembali</a>
    </div>
    <div class="card-body">
        {{-- form create asset --}}
    </div>
</div>@extends('layouts.admin.master')

@section('title', 'Tambah Master Asset - Admin')
@section('sub-title', 'Master Asset')
@section('breadcrumbExtra', 'Tambah Master Asset')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.assets.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.assets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.master_asset._form', ['asset' => null])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.assets.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection