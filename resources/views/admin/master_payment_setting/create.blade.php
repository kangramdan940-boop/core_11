@extends('layouts.admin.master')

@section('title', 'Tambah Management Payment - Admin')
@section('sub-title', 'Management Payment')
@section('breadcrumbExtra', 'Tambah Konfigurasi')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.payment-settings.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.payment-settings.store') }}" method="POST">
                @csrf
                @include('admin.master_payment_setting._form', ['setting' => null])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.payment-settings.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection