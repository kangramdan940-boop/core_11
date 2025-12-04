@extends('layouts.admin')

@section('title', 'Master Harga Emas - Admin')
@section('page_title', 'Master Harga Emas')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Harga Emas</h5>
        <a href="{{ route('admin.master.gold-prices.create') }}" class="btn btn-sm btn-primary">
            + Tambah Harga
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Source</th>
                            <th>Beli (IDR/g)</th>
                            <th>Jual (IDR/g)</th>
                            <th>Buyback (IDR/g)</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($prices as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ optional($p->price_date)->format('Y-m-d') }}</td>
                                <td>{{ $p->source }}</td>
                                <td>{{ number_format((float)$p->price_buy, 2, ',', '.') }}</td>
                                <td>{{ number_format((float)$p->price_sell, 2, ',', '.') }}</td>
                                <td>{{ $p->price_buyback !== null ? number_format((float)$p->price_buyback, 2, ',', '.') : '-' }}</td>
                                <td>
                                    @if($p->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.gold-prices.edit', $p) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.master.gold-prices.destroy', $p) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus harga ini?')">
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
                                    Belum ada data harga emas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($prices->hasPages())
                <div class="p-2">
                    {{ $prices->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection