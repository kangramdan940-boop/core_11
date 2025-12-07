@extends('layouts.admin')

@section('title', 'Tambah Role - Admin')
@section('page_title', 'Tambah Role')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                @include('admin.roles._form')
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection