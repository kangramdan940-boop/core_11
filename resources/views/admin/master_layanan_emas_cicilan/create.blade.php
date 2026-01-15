
@extends('layouts.admin.master')

@section('title', 'Tambah Layanan Emas Cicilan - Admin')
@section('sub-title', 'Layanan Emas Cicilan')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.layanan-emas-cicilan.index'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Layanan Emas Cicilan</h5>
            <a href="{{ route('admin.master.layanan-emas-cicilan.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.master.layanan-emas-cicilan.store') }}" method="POST">
                @csrf
                @include('admin.master_layanan_emas_cicilan._form')
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit"><i class="ri-check-line"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection