@extends('layouts.admin')

@section('title', 'Edit Role - Admin')
@section('page_title', 'Edit Role')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.roles._form', ['role' => $role])
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection