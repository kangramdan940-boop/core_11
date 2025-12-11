@extends('layouts.admin.master')

@section('title', 'Tambah Menu Home Customer - Admin')
@section('sub-title', 'Menu Home Customer')
@section('breadcrumbExtra', 'Tambah Menu Home Customer')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.menu-home-customer.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.menu-home-customer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.master_menu_home_customer._form', ['menu' => null])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.menu-home-customer.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection