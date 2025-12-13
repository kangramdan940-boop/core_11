@extends('layouts.admin.master')

@section('title', 'Set Password Sys User - Admin')
@section('sub-title', 'Mitra Brankas')
@section('breadcrumbExtra', 'Set Password')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.mitra-brankas.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Set Password Sys User Mitra</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div><strong>Mitra:</strong> {{ $mitra->nama_lengkap ?? '-' }}</div>
                <div><strong>Email:</strong> {{ $mitra->email ?? '-' }}</div>
                <div><strong>Status User:</strong> {{ $user ? 'Sudah ada akun' : 'Belum ada akun' }}</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.master.mitra-brankas.set-password.update', $mitra) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12 col-md-6">
                    <label class="form-label" for="Password">Password Baru</label>
                    <input type="password" id="Password" name="password" class="form-control" required minlength="8" autocomplete="new-password">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="PasswordConfirm">Konfirmasi Password</label>
                    <input type="password" id="PasswordConfirm" name="password_confirmation" class="form-control" required minlength="8" autocomplete="new-password">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.master.mitra-brankas.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection