@extends('layouts.admin')

@section('title', 'Tambah Gramasi Emas - Admin')
@section('page_title', 'Tambah Gramasi Emas')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.gramasi-emas.store') }}" method="POST">
                @csrf
                @include('admin.master_gramasi_emas._form')
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.master.gramasi-emas.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection