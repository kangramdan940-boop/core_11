@extends('layouts.admin')

@section('title', 'Master Agen - Admin')
@section('page_title', 'Master Agen')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Agen</h5>
        <a href="{{ route('admin.master.agens.create') }}" class="btn btn-sm btn-primary">
            + Tambah Agen
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
                            <th>Email</th>
                            <th>WhatsApp</th>
                            <th>Kode Agen</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agens as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                <td>{{ $a->name }}</td>
                                <td>{{ $a->email }}</td>
                                <td>{{ $a->phone_wa }}</td>
                                <td>{{ $a->kode_agen }}</td>
                                <td>{{ $a->area }}</td>
                                <td>
                                    @if($a->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.agens.edit', $a) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.master.agens.destroy', $a) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus agen ini?')">
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
                                <td colspan="8" class="text-center py-3">
                                    Belum ada data agen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($agens->hasPages())
                <div class="p-2">
                    {{ $agens->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection