@extends('layouts.admin')

@section('title', 'Edit Hak Akses - Admin')
@section('page_title', 'Edit Hak Akses')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.permissions.users.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Pengguna</h6>
            <div class="mb-3">
                <div><strong>Nama:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
                <div><strong>Role (kolom):</strong> <span class="badge bg-secondary">{{ $user->role }}</span></div>
            </div>

            <form action="{{ route('admin.permissions.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <h6 class="mb-2">Roles</h6>
                    <div class="row">
                        @foreach ($roles as $r)
                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="roles[]" value="{{ $r->name }}"
                                           @checked($user->hasRole($r->name))>
                                    {{ $r->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="mb-2">Permissions</h6>
                    <div class="row">
                        @foreach ($permissions as $p)
                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $p->name }}"
                                           @checked($user->hasPermissionTo($p->name))>
                                    {{ $p->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection