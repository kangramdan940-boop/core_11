@extends('layouts.admin.master')

@section('title', 'Keranjang - Admin')
@section('sub-title', 'Transaksi Keranjang')
@section('breadcrumbExtra', 'Keranjang')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.keranjang.index'))

@section('content')
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form action="{{ route('admin.trans.keranjang.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Tanggal Dibuat</label>
                <input type="date" name="created_date" class="form-control" value="{{ request('created_date') }}">
            </div>
            <div class="col-12 col-md-5">
                <label class="form-label mb-1">Status Order</label>
                <select name="status" class="form-select">
                    @php($allowed = ['' => 'Semua','perlu_dibayar' => 'Perlu Dibayar','dikemas' => 'Dikemas','dikirim' => 'Dikirim','selesai' => 'Selesai','dibatalkan' => 'Dibatalkan'])
                    @foreach($allowed as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.trans.keranjang.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <table class="table table-hover align-middle table-nowrap w-100">
        <thead class="bg-light bg-opacity-30">
            <tr>
                <th>No</th>
                <th>Kode Keranjang</th>
                <th>Status Order</th>
                <th>Status Kadaluarsa</th>
                <th>Kadaluarsa</th>
                <th>Ongkos Kirim</th>
                <th>Jumlah PO</th>
                <th>Bukti Transfer</th>
                <th>Dibuat</th>
                <th style="width:90px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keranjangs as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->kode_keranjang }}</td>
                    <td><span class="badge bg-secondary">{{ strtoupper((string)($k->status_order ?? '-')) }}</span></td>
                    <td><span class="badge {{ ($k->status_kadaluarsa === 'expired') ? 'bg-danger' : 'bg-success' }}">{{ strtoupper((string)($k->status_kadaluarsa ?? '-')) }}</span></td>
                    <td>{{ optional($k->expires_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    <td>{{ number_format((float)($k->ongkos_kirim ?? 0), 2, ',', '.') }}</td>
                    <td>{{ (int)($k->pos_count ?? 0) }}</td>
                    <td>
                        @if(!empty($k->bukti_transfer_url))
                            <a href="#" class="proof-preview" data-bs-toggle="modal" data-bs-target="#proofPreviewModal-{{ $k->id }}">
                                <img src="{{ asset($k->bukti_transfer_url) }}" alt="Bukti Transfer" style="height:70px; width:auto;" />
                            </a>
                            <div class="modal fade" id="proofPreviewModal-{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Preview Bukti Transfer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset($k->bukti_transfer_url) }}" alt="Preview Bukti Transfer" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ optional($k->created_at)->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.trans.keranjang.show', $k) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                    </td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>
@endsection