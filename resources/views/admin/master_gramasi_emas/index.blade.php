@extends('layouts.admin')

@section('title', 'Master Gramasi Emas - Admin')
@section('page_title', 'Master Gramasi Emas')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Gramasi Emas</h5>
        <a href="{{ route('admin.master.gramasi-emas.create') }}" class="btn btn-sm btn-primary">+ Tambah Gramasi</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Gramasi</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gramasis as $g)
                            <tr>
                                <td>{{ $g->id }}</td>
                                <td>{{ $g->nama }}</td>
                                <td>{{ number_format($g->gramasi, 3) }} g</td>
                                <td>
                                    @if($g->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.gramasi-emas.edit', $g) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.master.gramasi-emas.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus gramasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3">Belum ada data gramasi emas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($gramasis->hasPages())
                <div class="p-2">{{ $gramasis->links() }}</div>
            @endif
        </div>
    </div>
@endsection