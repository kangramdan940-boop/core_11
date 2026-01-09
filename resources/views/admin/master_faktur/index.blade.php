@extends('layouts.admin.master')

@section('title', 'Faktur Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Faktur Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.faktur.index'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Faktur Emas</span>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari invoice, customer, payment no">
                    <button class="btn btn-sm btn-outline-primary" type="submit">Cari</button>
                </form>
                <a href="{{ route('admin.master.faktur.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Nama Mitra Brankas</th>
                        <th>Harga yang Dibayar</th>
                        <th>Total Komisi</th>
                        <th>No Pembayaran</th>
                        <th>Grand Total</th>
                        <th>Items</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $d)
                        <tr>
                            <td>{{ $d->invoice_number ?? '-' }}</td>
                            <td>{{ $d->date ? \Carbon\Carbon::parse($d->date)->format("Y-m-d") : ($d->date_raw ?? '-') }}</td>
                            <td>
                                @php($km = $komisiMap[$d->id] ?? null)
                                @php($mid = $km?->id_mitra)
                                @php($mitra = $mid ? $mitras->firstWhere('id', $mid) : null)
                                @if($mitra)
                                    <a href="{{ route('admin.master.mitra-brankas.edit', $mitra->id) }}" target="_blank" rel="noopener noreferrer">{{ $mitra->nama_lengkap }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php($valPaid = $km?->harga_yang_dibayar ?? null)
                                {{ $valPaid !== null ? ('Rp ' . number_format((float)$valPaid, 0, ',', '.')) : '-' }}
                            </td>
                            <td>
                                @php($valKom = $km?->total_komisi ?? null)
                                {{ $valKom !== null ? ('Rp ' . number_format((float)$valKom, 0, ',', '.')) : '-' }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#komisiModal-{{ $d->id }}">{{ isset($komisiMap[$d->id]) ? 'Edit Komisi' : 'Upload Komisi' }}</button>
                                <div class="modal fade" id="komisiModal-{{ $d->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Upload Pembayaran Komisi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.master.faktur.komisi.store', $d) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_faktur" value="{{ $d->id }}">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Tanggal</label>
                                                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', isset($komisiMap[$d->id]) ? $komisiMap[$d->id]->tanggal : now()->toDateString()) }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Mitra Brankas</label>
                                                            <select name="id_mitra" id="idMitraSelect-{{ $d->id }}" class="form-select">
                                                                <option value="">- Pilih Mitra -</option>
                                                                @foreach($mitras as $m)
                                                                    <option value="{{ $m->id }}" {{ (string) old('id_mitra', isset($komisiMap[$d->id]) ? $komisiMap[$d->id]->id_mitra : '') === (string) $m->id ? 'selected' : '' }}>{{ $m->nama_lengkap }} ({{ $m->kode_mitra }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            var el = document.getElementById('idMitraSelect-{{ $d->id }}');
                                                            if (el && typeof Choices !== 'undefined') {
                                                                new Choices(el, {
                                                                    searchEnabled: true,
                                                                    placeholder: true,
                                                                    itemSelectText: '',
                                                                });
                                                            }
                                                        });
                                                        </script>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Harga yang Dibayar</label>
                                                            <input type="number" step="0.01" name="harga_yang_dibayar" class="form-control" value="{{ old('harga_yang_dibayar', isset($komisiMap[$d->id]) ? $komisiMap[$d->id]->harga_yang_dibayar : '') }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Total Komisi</label>
                                                            <input type="number" step="0.01" name="total_komisi" class="form-control" value="{{ old('total_komisi', isset($komisiMap[$d->id]) ? $komisiMap[$d->id]->total_komisi : '') }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Struk Pembayaran</label>
                                                            <input type="file" name="file_struk_pembayaran" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                            @php($fp = isset($komisiMap[$d->id]) ? ($komisiMap[$d->id]->file_struk_pembayaran ?? null) : null)
                                                            @if($fp)
                                                                @php($ext = strtolower(pathinfo($fp, PATHINFO_EXTENSION)))
                                                                @if(in_array($ext, ['jpg','jpeg','png']))
                                                                    <img src="{{ asset($fp) }}" alt="Struk Pembayaran" class="img-fluid w-100 rounded border mt-2" style="height:240px; object-fit:contain;">
                                                                @elseif($ext === 'pdf')
                                                                    <iframe src="{{ asset($fp) }}" title="Struk Pembayaran" class="mt-2 w-100 rounded border" style="height:240px;"></iframe>
                                                                @else
                                                                    <a href="{{ asset($fp) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2 w-100">Lihat File</a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Struk Komisi</label>
                                                            <input type="file" name="file_struk_komisi" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                            @php($fk = isset($komisiMap[$d->id]) ? ($komisiMap[$d->id]->file_struk_komisi ?? null) : null)
                                                            @if($fk)
                                                                @php($ext2 = strtolower(pathinfo($fk, PATHINFO_EXTENSION)))
                                                                @if(in_array($ext2, ['jpg','jpeg','png']))
                                                                    <img src="{{ asset($fk) }}" alt="Struk Komisi" class="img-fluid w-100 rounded border mt-2" style="height:240px; object-fit:contain;">
                                                                @elseif($ext2 === 'pdf')
                                                                    <iframe src="{{ asset($fk) }}" title="Struk Komisi" class="mt-2 w-100 rounded border" style="height:240px;"></iframe>
                                                                @else
                                                                    <a href="{{ asset($fk) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2 w-100">Lihat File</a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>Rp {{ number_format((int)($d->grand_total_idr ?? 0), 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ $d->items_count }}</span></td>
                            <td>
                                <a href="{{ route('admin.master.faktur.show', $d) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                <a href="{{ route('admin.master.faktur.edit', $d) }}" class="btn btn-sm btn-outline-secondary ms-1">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $documents->links() }}
        </div>
    </div>
@endsection