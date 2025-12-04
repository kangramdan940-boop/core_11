@extends('layouts.admin')

@section('title', 'Master Stok Emas Ready - Admin')
@section('page_title', 'Master Stok Emas Ready')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Stok Emas Ready</h5>
        <a href="{{ route('admin.master.ready-stocks.create') }}" class="btn btn-sm btn-primary">
            + Tambah Stok
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode Item</th>
                            <th>Brand</th>
                            <th>Gramasi (g)</th>
                            <th>Agen</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>Harga Jual Fix</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ $s->kode_item }}</td>
                                <td>{{ $s->brand }}</td>
                                <td>{{ number_format((float)$s->gramasi, 3, ',', '.') }}</td>
                                <td>{{ optional($s->agen)->name ?? '-' }}</td>
                                <td>{{ ucfirst($s->kondisi_barang) }}</td>
                                <td>
                                    @if($s->status === 'available')
                                        <span class="badge bg-success">Available</span>
                                    @elseif($s->status === 'reserved')
                                        <span class="badge bg-warning text-dark">Reserved</span>
                                    @else
                                        <span class="badge bg-secondary">Sold</span>
                                    @endif
                                </td>
                                <td>{{ $s->harga_jual_fix !== null ? number_format((float)$s->harga_jual_fix, 2, ',', '.') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.master.ready-stocks.edit', $s) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.master.ready-stocks.destroy', $s) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus stok ini?')">
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
                                <td colspan="9" class="text-center py-3">
                                    Belum ada data stok emas ready.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stocks->hasPages())
                <div class="p-2">
                    {{ $stocks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection