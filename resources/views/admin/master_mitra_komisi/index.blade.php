@extends('layouts.admin')

@section('title', 'Master Komisi Mitra - Admin')
@section('page_title', 'Master Komisi Mitra')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Komisi Mitra</h5>
        <a href="{{ route('admin.master.mitra-komisi.create') }}" class="btn btn-sm btn-primary">
            + Tambah Komisi
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Mitra</th>
                            <th>Tipe Transaksi</th>
                            <th>Komisi (%)</th>
                            <th>Komisi Bulan Ini (IDR)</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($komisis as $k)
                            <tr>
                                <td>{{ $k->id }}</td>
                                <td>{{ optional($k->mitra)->nama_lengkap ?? '-' }}</td>
                                <td>{{ strtoupper($k->tipe_transaksi) }}</td>
                                <td>{{ number_format((float)$k->komisi_persen, 2, ',', '.') }}</td>
                                <td>{{ number_format((float)($monthSummaries[optional($k->mitra)->id] ?? 0), 2, ',', '.') }}</td>
                                <td>
                                    @php
                                        $mulai = $k->berlaku_mulai ? $k->berlaku_mulai->format('Y-m-d') : '-';
                                        $sampai = $k->berlaku_sampai ? $k->berlaku_sampai->format('Y-m-d') : '-';
                                    @endphp
                                    {{ $mulai }} s/d {{ $sampai }}
                                </td>
                                <td>
                                    @if($k->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.mitra-komisi.edit', $k) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.master.mitra-komisi.destroy', $k) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus komisi ini?')">
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
                                <td colspan="7" class="text-center py-3">
                                    Belum ada data komisi mitra.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($komisis->hasPages())
                <div class="p-2">
                    {{ $komisis->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection