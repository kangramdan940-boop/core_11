@extends('layouts.admin.master')

@section('title', 'Edit Management Mobile Apps - Admin')
@section('sub-title', 'Management Mobile Apps')
@section('breadcrumbExtra', 'Edit Konfigurasi')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.mobile-app-configs.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.mobile-app-configs.update', $config) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.master_mobile_app_configs._form', ['config' => $config])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.mobile-app-configs.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection