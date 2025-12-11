@extends('layouts.admin.master')

@section('title', 'Tambah Harga Emas - Admin')
@section('sub-title', 'Harga Emas')
@section('breadcrumbExtra', 'Tambah Harga Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.gold-prices.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.gold-prices.store') }}" method="POST">
                @csrf

                @include('admin.master_gold_price._form', ['price' => null])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.gold-prices.index') }}" class="btn btn-outline-secondary">Batal</a>
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

