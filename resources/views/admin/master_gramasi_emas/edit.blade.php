@extends('layouts.admin')

@section('title', 'Edit Gramasi Emas - Admin')
@section('page_title', 'Edit Gramasi Emas')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.gramasi-emas.update', $item) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.master_gramasi_emas._form', ['item' => $item])
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.master.gramasi-emas.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection