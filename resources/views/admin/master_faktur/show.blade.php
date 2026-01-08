@extends('layouts.admin.master')

@section('title', 'Detail Faktur Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Detail Faktur')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.faktur.index'))

@section('content')
    <div class="card mb-3">
        <div class="card-header">Informasi Faktur</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><div class="fw-semibold">Invoice</div><div>{{ $document->invoice_number ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">Tanggal</div><div>{{ $document->date ? $document->date->format('Y-m-d') : ($document->date_raw ?? '-') }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">Customer</div><div>{{ $document->customer_name ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">Keanggotaan</div><div>{{ $document->membership_number ?? '-' }} {{ $document->membership_tier ? ' - '.$document->membership_tier : '' }}</div></div>
                <div class="col-md-4"><div class="fw-semibold">Jenis Transaksi</div><div>{{ $document->transaction_type ?? '-' }}</div></div>
                <div class="col-md-4"><div class="fw-semibold">Butik</div><div>{{ $document->boutique_code_name ?? '-' }}</div></div>
                <div class="col-md-4"><div class="fw-semibold">Lokasi Butik</div><div>{{ $document->boutique_location ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Issuer</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><div class="fw-semibold">Perusahaan</div><div>{{ $document->issuer_company ?? '-' }}</div></div>
                <div class="col-md-4"><div class="fw-semibold">Unit Bisnis</div><div>{{ $document->issuer_business_unit ?? '-' }}</div></div>
                <div class="col-md-4"><div class="fw-semibold">Website</div><div>{{ $document->issuer_website ?? '-' }}</div></div>
                <div class="col-md-6"><div class="fw-semibold">Alamat</div><div>{{ $document->issuer_address ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">Telp</div><div>{{ $document->issuer_phone ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">NPWP</div><div>{{ $document->issuer_npwp ?? '-' }}</div></div>
                <div class="col-md-6"><div class="fw-semibold">Pemegang NPWP</div><div>{{ $document->issuer_npwp_holder ?? '-' }}</div></div>
                <div class="col-md-6"><div class="fw-semibold">Alamat NPWP</div><div>{{ $document->issuer_npwp_address ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Penerima Kuasa</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><div class="fw-semibold">Nama</div><div>{{ $document->authorized_receiver_name ?? '-' }}</div></div>
                <div class="col-md-6"><div class="fw-semibold">NIK</div><div>{{ $document->authorized_receiver_nik ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Pembayaran</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><div class="fw-semibold">Metode</div><div>{{ $document->payment_method ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">VA</div><div>{{ $document->virtual_account ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">No Pembayaran</div><div>{{ $document->payment_no ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">Created By</div><div>{{ $document->created_by ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">Print By</div><div>{{ $document->print_by ?? '-' }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">DPP</div><div>Rp {{ number_format((int)($document->dpp_idr ?? 0), 0, ',', '.') }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">PPN</div><div>{{ $document->ppn_rate ? $document->ppn_rate.'%' : '-' }} • Rp {{ number_format((int)($document->ppn_idr ?? 0), 0, ',', '.') }}</div></div>
                <div class="col-md-3"><div class="fw-semibold">Grand Total</div><div>Rp {{ number_format((int)($document->grand_total_idr ?? 0), 0, ',', '.') }}</div></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Items</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Deskripsi</th>
                        <th>Qty</th>
                        <th>Berat (kg)</th>
                        <th>Gramasi (gr)</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($document->items as $it)
                        <tr>
                            <td>{{ $it->no }}</td>
                            <td>{{ $it->description }}</td>
                            <td>{{ $it->quantity_pcs }}</td>
                            <td>{{ number_format((float)$it->weight_kg, 6, '.', '') }}</td>
                            <td>{{ number_format((float)$it->weight_kg * 1000, 3, '.', '') }}</td>
                            <td>Rp {{ number_format((int)$it->unit_price_idr, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format((int)$it->total_idr, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada item</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($document->raw_text)
    <div class="card mb-3">
        <div class="card-header">Raw Text</div>
        <div class="card-body">
            <pre class="form-control" style="white-space:pre-wrap;min-height:160px;">{{ $document->raw_text }}</pre>
        </div>
    </div>
    @endif

    @if(is_array($document->notes) && count($document->notes))
    <div class="card mb-3">
        <div class="card-header">Catatan</div>
        <div class="card-body">
            <ul class="mb-0">
                @foreach($document->notes as $n)
                    <li>{{ $n }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
@endsection