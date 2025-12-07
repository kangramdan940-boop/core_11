@extends('layouts.admin')

@section('title', 'Manajemen Roles - Admin')
@section('page_title', 'Manajemen Roles')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Roles</h5>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">
            + Tambah Role
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Guard</th>
                            <th style="width:160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->name }}</td>
                                <td>{{ $r->guard_name }}</td>
                                <td>
                                    <a href="{{ route('admin.roles.edit', $r) }}" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.roles.destroy', $r) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus role ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Belum ada role.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($roles->hasPages())
                <div class="p-2">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection