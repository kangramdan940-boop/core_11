@extends('layouts.admin.master')

@section('title', 'Edit Agen - Admin')
@section('sub-title', 'Agen')
@section('breadcrumbExtra', 'Edit Agen')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.agens.index'))

@section('content')
    <div class="mb-2">
        <small class="text-muted">View: resources/views/admin/master_agen/edit.blade.php • Route: admin.master.agens.edit • URL: /admin/master/agens/{agen}/edit</small>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.agens.update', $agen) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_agen._form', ['agen' => $agen])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.agens.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
