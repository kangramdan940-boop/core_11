@extends('layouts.admin')

@section('title', 'Master Mitra Brankas - Admin')
@section('page_title', 'Master Mitra Brankas')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Mitra Brankas</h5>
        <a href="{{ route('admin.master.mitra-brankas.create') }}" class="btn btn-sm btn-primary">
            + Tambah Mitra
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>WhatsApp</th>
                            <th>Kode Mitra</th>
                            <th>Platform</th>
                            <th>Limit Harian (g)</th>
                            <th>Komisi (%)</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mitras as $m)
                            <tr>
                                <td>{{ $m->id }}</td>
                                <td>{{ $m->nama_lengkap }}</td>
                                <td>{{ $m->email }}</td>
                                <td>{{ $m->phone_wa }}</td>
                                <td>{{ $m->kode_mitra }}</td>
                                <td>{{ $m->platform }}</td>
                                <td>{{ number_format((float)$m->harian_limit_gram, 3) }}</td>
                                <td>{{ number_format((float)$m->komisi_persen, 2) }}</td>
                                <td>
                                    @if($m->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.mitra-brankas.edit', $m) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.master.mitra-brankas.destroy', $m) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus mitra ini?')">
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
                                <td colspan="10" class="text-center py-3">
                                    Belum ada data mitra.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($mitras->hasPages())
                <div class="p-2">
                    {{ $mitras->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection