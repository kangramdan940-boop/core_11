@extends('layouts.admin.master')

@section('title', 'Tambah Stok Emas Ready - Admin')
@section('sub-title', 'Stok Emas Ready')
@section('breadcrumbExtra', 'Tambah Stok Emas Ready')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.ready-stocks.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.ready-stocks.store') }}" method="POST">
                @csrf

                @include('admin.master_gold_ready_stock._form', ['stock' => null, 'agens' => $agens])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.ready-stocks.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
