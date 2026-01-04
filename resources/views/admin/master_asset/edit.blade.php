@extends('layouts.admin.master')

@section('title', 'Edit Master Asset - Admin')
@section('sub-title', 'Master Asset')
@section('breadcrumbExtra', 'Edit Master Asset')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.assets.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Edit Asset</h6>
            <a href="{{ route('admin.master.assets.index') }}" class="btn btn-outline-secondary btn-sm px-2" style="height:28px;">← Kembali</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.master.assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.master_asset._form', ['asset' => $asset])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.assets.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection