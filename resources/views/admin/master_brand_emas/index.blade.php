@extends('layouts.admin')

@section('title', 'Master Brand Emas - Admin')
@section('page_title', 'Master Brand Emas')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Brand Emas</h5>
        <a href="{{ route('admin.master.brand-emas.create') }}" class="btn btn-sm btn-primary">+ Tambah Brand</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $b)
                            <tr>
                                <td>{{ $b->id }}</td>
                                <td>
                                    @if($b->image_url)
                                        <img src="{{ Str::startsWith($b->image_url, ['http://','https://']) ? $b->image_url : asset('storage/' . $b->image_url) }}"
                                             alt="logo" style="height:32px;object-fit:contain;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $b->kode_brand }}</td>
                                <td>{{ $b->nama_brand }}</td>
                                <td>
                                    @if($b->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.brand-emas.edit', $b) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.master.brand-emas.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus brand ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-3">Belum ada data brand emas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($brands->hasPages())
                <div class="p-2">{{ $brands->links() }}</div>
            @endif
        </div>
    </div>
@endsection