@extends('layouts.admin.master')

@section('title', 'Tambah Mitra Brankas - Admin')
@section('sub-title', 'Mitra Brankas')
@section('breadcrumbExtra', 'Tambah Mitra Brankas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.mitra-brankas.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.mitra-brankas.store') }}" method="POST">
                @csrf

                @include('admin.master_mitra_brankas._form', ['mitra' => null])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.mitra-brankas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
