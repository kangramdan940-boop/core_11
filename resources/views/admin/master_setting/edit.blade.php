@extends('layouts.admin.master')

@section('title', 'Edit Setting - Admin')
@section('sub-title', 'Master Setting')
@section('breadcrumbExtra', 'Edit Setting')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.settings.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.settings.update', $setting) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_setting._form', ['setting' => $setting])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.settings.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
