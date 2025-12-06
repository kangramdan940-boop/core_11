@extends('layouts.admin.master')

@section('title', 'Edit Admin - Admin')
@section('sub-title', 'Master Admin')
@section('breadcrumbExtra', 'Edit Admin')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.admins.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.admins.update', $admin) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_admin._form', ['admin' => $admin])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.admins.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
