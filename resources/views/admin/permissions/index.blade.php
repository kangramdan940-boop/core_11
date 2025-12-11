@extends('layouts.admin')

@section('title', 'Hak Akses - Admin')
@section('page_title', 'Hak Akses')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Pengguna</h5>
    </div>

    <div class="card shadow-sm">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role (kolom)</th>
                    <th>Roles (Spatie)</th>
                    <th>Permissions (Spatie)</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge bg-secondary">{{ $u->role }}</span></td>
                        <td>
                            @foreach ($u->roles as $r)
                                <span class="badge bg-primary">{{ $r->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($u->permissions as $p)
                                <span class="badge bg-info text-dark">{{ $p->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.permissions.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-3">Belum ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($users->hasPages())
            <div class="p-2">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection