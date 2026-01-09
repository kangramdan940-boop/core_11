@extends('layouts.admin.master')

@section('title', 'Tambah User - Management Login')
@section('pagetitle', 'Dashboard')
@section('sub-title', 'System')
@section('breadcrumbExtra', 'Management Login')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header"><h5 class="mb-0">Tambah User</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.management-login.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12 col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required maxlength="150" value="{{ old('name') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required maxlength="150" value="{{ old('email') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        @foreach($roles as $r)
                            <option value="{{ $r }}" @selected(old('role')===$r)>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Aktif</label>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_active" id="isActive" class="form-check-input" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">User aktif</label>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="8" autocomplete="new-password">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="8" autocomplete="new-password">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.management-login.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection