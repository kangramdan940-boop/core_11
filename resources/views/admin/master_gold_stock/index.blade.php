@extends('layouts.admin.master')

@section('title', 'Master Stok Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Stok Emas (Antam)')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.gold-stocks.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <div class="h6 mb-0">Daftar Stok Emas</div>
                <a href="{{ route('admin.master.gold-stocks.create') }}" class="btn btn-primary btn-sm">
                    Tambah
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80px;">ID</th>
                            <th>Mitra</th>
                            <th>No Faktur</th>
                            <th class="text-end">Gramasi (g)</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Berat (g)</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Total Pembayaran</th>
                            <th>File Faktur</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ optional($s->mitra)->nama_lengkap ?? '-' }}</td>
                                <td>{{ $s->no_faktur ?? '-' }}</td>
                                <td class="text-end">{{ number_format((float)($s->gramasi ?? 0), 3) }}</td>
                                <td class="text-end">{{ (int)($s->qty ?? 0) }}</td>
                                <td class="text-end">{{ number_format((float)($s->berat ?? 0), 3) }}</td>
                                <td class="text-end">Rp {{ number_format((float)($s->harga ?? 0), 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format((float)($s->total_pembayaran ?? 0), 2, ',', '.') }}</td>
                                <td>
                                    @php($raw = $s->file_faktur_url ?? '')
                                    @php($href = Str::startsWith($raw, ['http://','https://','/']) ? $raw : ($raw ? asset($raw) : ''))
                                    @if(!empty($href))
                                        <a href="{{ $href }}" target="_blank" rel="noopener">Buka</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.gold-stocks.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.master.gold-stocks.destroy', $s) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-muted">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection