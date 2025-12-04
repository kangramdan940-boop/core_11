@extends('layouts.admin')

@section('title', 'Detail Cicilan Emas - Admin')
@section('page_title', 'Detail Cicilan Emas')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.trans.cicilan.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan Kontrak</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode Kontrak</strong><br>{{ $contract->kode_kontrak }}</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($contract->status) }}</div>
                <div class="col-md-3"><strong>Gramasi</strong><br>{{ number_format((float)$contract->gramasi, 3, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Total Kontrak</strong><br>{{ number_format((float)$contract->harga_total_kontrak, 2, ',', '.') }}</div>

                <div class="col-md-3"><strong>Tenor</strong><br>{{ $contract->tenor_bulan }} bln</div>
                <div class="col-md-3"><strong>Cicilan / Bulan</strong><br>{{ number_format((float)$contract->cicilan_per_bulan, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>DP</strong><br>{{ number_format((float)$contract->dp_amount, 2, ',', '.') }} ({{ number_format((float)$contract->dp_persen, 2, ',', '.') }}%)</div>
                <div class="col-md-3"><strong>Terbayar</strong><br>{{ number_format((float)$contract->total_sudah_dibayar, 2, ',', '.') }}</div>

                <div class="col-md-3"><strong>Sisa Tagihan</strong><br>{{ number_format((float)$contract->sisa_tagihan, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Mulai</strong><br>{{ optional($contract->mulai_kontrak)->format('Y-m-d') ?? '-' }}</div>
                <div class="col-md-3"><strong>Jatuh Tempo</strong><br>{{ optional($contract->jatuh_tempo_kontrak)->format('Y-m-d') ?? '-' }}</div>
                <div class="col-md-3"><strong>Last Paid</strong><br>{{ optional($contract->last_paid_at)->format('Y-m-d H:i') ?? '-' }}</div>

                <div class="col-md-3"><strong>Customer</strong><br>{{ optional($contract->customer)->full_name ?? '-' }}</div>
                <div class="col-md-3"><strong>Agen</strong><br>{{ optional($contract->agen)->name ?? '-' }}</div>
                <div class="col-md-3"><strong>Delivery</strong><br>{{ $contract->delivery_type }}</div>
                <div class="col-md-3"><strong>Catatan</strong><br>{{ $contract->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Data Pengiriman (setelah lunas)</h6>
            <div class="row g-3">
                <div class="col-md-6"><strong>Nama</strong><br>{{ $contract->shipping_name ?? '-' }}</div>
                <div class="col-md-6"><strong>WhatsApp</strong><br>{{ $contract->shipping_phone ?? '-' }}</div>
                <div class="col-md-12"><strong>Alamat</strong><br>{{ $contract->shipping_address ?? '-' }}</div>
                <div class="col-md-4"><strong>Kota</strong><br>{{ $contract->shipping_city ?? '-' }}</div>
                <div class="col-md-4"><strong>Provinsi</strong><br>{{ $contract->shipping_province ?? '-' }}</div>
                <div class="col-md-4"><strong>Kode Pos</strong><br>{{ $contract->shipping_postal_code ?? '-' }}</div>
                <div class="col-md-3"><strong>Dikirim</strong><br>{{ optional($contract->shipped_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Diterima</strong><br>{{ optional($contract->received_at)->format('Y-m-d H:i') ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Jadwal & Pembayaran Cicilan</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th>Amount Due</th>
                            <th>Status</th>
                            <th>Paid At</th>
                            <th>Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $p)
                            <tr>
                                <td>{{ $p->cicilan_ke }}</td>
                                <td>{{ optional($p->due_date)->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ number_format((float)$p->amount_due, 2, ',', '.') }}</td>
                                <td>{{ strtoupper($p->status) }}</td>
                                <td>{{ optional($p->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ number_format((float)$p->amount_paid, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">Belum ada jadwal pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h6 class="mb-3">Konfirmasi Pembayaran Manual dari Customer</h6>
            @php
                $paymentLogIds = $payments->pluck('id');
                $paymentLogs = \App\Models\TransPaymentLog::where('ref_type', 'cicilan_payment')
                    ->whereIn('ref_id', $paymentLogIds)
                    ->orderByDesc('id')
                    ->get();
            @endphp
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode Payment</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Metode</th>
                            <th>Provider</th>
                            <th>Paid At</th>
                            <th style="width:160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paymentLogs as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->kode_payment }}</td>
                                <td>{{ strtoupper($l->status) }}</td>
                                <td>{{ number_format((float)$l->amount, 2, ',', '.') }} {{ $l->currency }}</td>
                                <td>{{ $l->payment_method ?? '-' }}</td>
                                <td>{{ $l->provider ?? '-' }}</td>
                                <td>{{ optional($l->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.trans.payment-logs.show', $l) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    @if ($l->payment_method === 'manual_transfer' && $l->status === 'pending')
                                    <form action="{{ route('admin.trans.payment-logs.approve', $l) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Terima</button>
                                    </form>
                                    <form action="{{ route('admin.trans.payment-logs.reject', $l) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">Belum ada konfirmasi pembayaran manual.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection