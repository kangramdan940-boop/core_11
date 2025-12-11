@extends('layouts.admin.master')

@section('title', 'Edit Gramasi Emas - Admin')
@section('sub-title', 'Gramasi Emas')
@section('breadcrumbExtra', 'Edit Gramasi Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.gramasi-emas.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.gramasi-emas.update', $item) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.master_gramasi_emas._form', ['item' => $item])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.gramasi-emas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
