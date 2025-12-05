@extends('layouts.admin')

@section('title', 'Master Produk & Layanan - Admin')
@section('page_title', 'Master Produk & Layanan')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Produk & Layanan</h5>
        <a href="{{ route('admin.master.produk-layanan.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Gramasi</th>
                            <th>Harga Hari Ini</th>
                            <th>Ready/PO</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i)
                            <tr>
                                <td>{{ $i->id }}</td>
                                <td>
                                    @if($i->image_produk)
                                        <img src="{{ Str::startsWith($i->image_produk, ['http://','https://']) ? $i->image_produk : asset('storage/' . $i->image_produk) }}"
                                             alt="produk" style="height:32px;object-fit:contain;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ optional($i->gramasi)->gramasi ? number_format($i->gramasi->gramasi, 3).' g' : '-' }}</td>
                                <td>{{ number_format($i->harga_hariini, 2) }}</td>
                                <td>
                                    <span class="badge {{ $i->is_allow_ready ? 'bg-success' : 'bg-secondary' }}">Ready</span>
                                    <span class="badge {{ $i->is_allow_po ? 'bg-success' : 'bg-secondary' }}">PO</span>
                                </td>
                                <td>{{ $i->status }}</td>
                                <td>
                                    <a href="{{ route('admin.master.produk-layanan.edit', $i) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.master.produk-layanan.destroy', $i) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-3">Belum ada data produk & layanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($items->hasPages())
                <div class="p-2">{{ $items->links() }}</div>
            @endif
        </div>
    </div>
@endsection