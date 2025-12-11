@extends('layouts.admin.master')

@section('title', 'Tambah Produk & Layanan - Admin')
@section('sub-title', 'Produk & Layanan')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.produk-layanan.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.produk-layanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.master_produk_dan_layanan._form')
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.produk-layanan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/input-masks.init.js') }}"></script>
    <script src="{{ asset('assets/libs/air-datepicker/air-datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/air-datepicker.init.js') }}"></script>
@endsection
