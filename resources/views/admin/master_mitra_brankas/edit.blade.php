@extends('layouts.admin.master')

@section('title', 'Edit Mitra Brankas - Admin')
@section('sub-title', 'Mitra Brankas')
@section('breadcrumbExtra', 'Edit Mitra Brankas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.mitra-brankas.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.mitra-brankas.update', $mitra) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_mitra_brankas._form', ['mitra' => $mitra])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.mitra-brankas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
