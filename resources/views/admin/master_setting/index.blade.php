@extends('layouts.admin')

@section('title', 'Master Setting - Admin')
@section('page_title', 'Master Setting')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Setting</h5>
        <a href="{{ route('admin.master.settings.create') }}" class="btn btn-sm btn-primary">
            + Tambah Setting
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Key</th>
                            <th>Label</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($settings as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ $s->key }}</td>
                                <td>{{ $s->label ?? '-' }}</td>
                                <td>{{ $s->group ?? '-' }}</td>
                                <td>
                                    @if($s->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.settings.edit', $s) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.master.settings.destroy', $s) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus setting ini?')">
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
                                <td colspan="6" class="text-center py-3">
                                    Belum ada data setting.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($settings->hasPages())
                <div class="p-2">
                    {{ $settings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection