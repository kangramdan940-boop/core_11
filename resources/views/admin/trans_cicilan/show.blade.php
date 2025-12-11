@extends('layouts.admin.master')

@section('title', 'Detail Cicilan Emas - Admin')
@section('sub-title', 'Transaksi Cicilan')
@section('breadcrumbExtra', 'Detail Cicilan Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan.index'))

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.trans.cicilan.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Ringkasan Kontrak</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Kode Kontrak</strong>
                    <div class="mt-1">{{ $contract->kode_kontrak }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Status</strong>
                    <div class="mt-1">{{ strtoupper($contract->status) }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Gramasi</strong>
                    <div class="mt-1">{{ number_format((float)$contract->gramasi, 3, ',', '.') }} g</div>
                </div>
                <div class="col-md-3">
                    <strong>Total Kontrak</strong>
                    <div class="mt-1">{{ number_format((float)$contract->harga_total_kontrak, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Tenor</strong>
                    <div class="mt-1">{{ $contract->tenor_bulan }} bln</div>
                </div>
                <div class="col-md-3">
                    <strong>Cicilan / Bulan</strong>
                    <div class="mt-1">{{ number_format((float)$contract->cicilan_per_bulan, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>DP</strong>
                    <div class="mt-1">{{ number_format((float)$contract->dp_amount, 2, ',', '.') }} ({{ number_format((float)$contract->dp_persen, 2, ',', '.') }}%)</div>
                </div>
                <div class="col-md-3">
                    <strong>Terbayar</strong>
                    <div class="mt-1">{{ number_format((float)$contract->total_sudah_dibayar, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Sisa Tagihan</strong>
                    <div class="mt-1">{{ number_format((float)$contract->sisa_tagihan, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Mulai</strong>
                    <div class="mt-1">{{ optional($contract->mulai_kontrak)->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Jatuh Tempo</strong>
                    <div class="mt-1">{{ optional($contract->jatuh_tempo_kontrak)->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Last Paid</strong>
                    <div class="mt-1">{{ optional($contract->last_paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Customer</strong>
                    <div class="mt-1">{{ optional($contract->customer)->full_name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Agen</strong>
                    <div class="mt-1">{{ optional($contract->agen)->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Delivery</strong>
                    <div class="mt-1">{{ $contract->delivery_type }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Catatan</strong>
                    <div class="mt-1">{{ $contract->catatan ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Data Pengiriman (setelah lunas)</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Nama</strong>
                    <div class="mt-1">{{ $contract->shipping_name ?? '-' }}</div>        
                </div>
                <div class="col-md-6">
                    <strong>WhatsApp</strong>
                    <div class="mt-1">{{ $contract->shipping_phone ?? '-' }}</div>        
                </div>
                <div class="col-md-12">
                    <strong>Alamat</strong>
                    <div class="mt-1">{{ $contract->shipping_address ?? '-' }}</div>        
                </div>
                <div class="col-md-4">
                    <strong>Kota</strong>
                    <div class="mt-1">{{ $contract->shipping_city ?? '-' }}</div>        
                </div>
                <div class="col-md-4">
                    <strong>Provinsi</strong>
                    <div class="mt-1">{{ $contract->shipping_province ?? '-' }}</div>        
                </div>
                <div class="col-md-4">
                    <strong>Kode Pos</strong>
                    <div class="mt-1">{{ $contract->shipping_postal_code ?? '-' }}</div>        
                </div>
                <div class="col-md-3">
                    <strong>Dikirim</strong>
                    <div class="mt-1">{{ optional($contract->shipped_at)->format('Y-m-d H:i') ?? '-' }}</div>        
                </div>
                <div class="col-md-3">
                    <strong>Diterima</strong>
                    <div class="mt-1">{{ optional($contract->received_at)->format('Y-m-d H:i') ?? '-' }}</div>        
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Jadwal & Pembayaran Cicilan</h6>
            <table id="cicilanPaymentsTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
                <thead class="bg-light bg-opacity-30">
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
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Konfirmasi Pembayaran Manual dari Customer</h6>
            @php
                $paymentLogIds = $payments->pluck('id');
                $paymentLogs = \App\Models\TransPaymentLog::where('ref_type', 'cicilan_payment')
                    ->whereIn('ref_id', $paymentLogIds)
                    ->orderByDesc('id')
                    ->get();
            @endphp
            <table id="cicilanManualLogsTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
                <thead class="bg-light bg-opacity-30">
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
                                <div class="hstack gap-2 fs-15">
                                    <a href="{{ route('admin.trans.payment-logs.show', $l) }}" class="btn icon-btn-sm btn-light-primary"><i class="bi bi-eye"></i></a>
                                    @if ($l->payment_method === 'manual_transfer' && $l->status === 'pending')
                                    <form action="{{ route('admin.trans.payment-logs.approve', $l) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="approve-btn btn icon-btn-sm btn-success"><i class="bi bi-check2"></i></button>
                                    </form>
                                    <form action="{{ route('admin.trans.payment-logs.reject', $l) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="reject-btn btn icon-btn-sm btn-outline-danger"><i class="bi bi-x"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.3.0/css/dataTables.checkboxes.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.3.0/js/dataTables.checkboxes.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.6.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const t1 = document.getElementById('cicilanPaymentsTable');
            if (t1 && typeof $ !== 'undefined' && $.fn.DataTable) {
                $('#cicilanPaymentsTable').DataTable({
                    responsive: false,
                    scrollX: true,
                    lengthMenu: [10, 20, 50],
                    pageLength: 10,
                    ordering: true,
                    order: [[0, 'asc']],
                    dom:
                        '<"card-header dt-head d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3"' +
                        '<"d-flex flex-column flex-sm-row align-items-center justify-content-sm-end gap-3 w-100"f>>' +
                        't' +
                        '<"card-footer d-flex flex-column align-items-center gap-2"' +
                        '<"row w-100 align-items-center g-2"' +
                            '<"col-12 col-md-5 d-flex align-items-center justify-content-md-start justify-content-center gap-2"l i>' +
                            '<"col-12 col-md-7 d-flex justify-content-md-end justify-content-center"p>' +
                        '>>',
                    language: { sLengthMenu: '_MENU_ ', search: '', searchPlaceholder: 'Search Schedules' }
                });
            }
            const t2 = document.getElementById('cicilanManualLogsTable');
            if (t2 && typeof $ !== 'undefined' && $.fn.DataTable) {
                $('#cicilanManualLogsTable').DataTable({
                    responsive: false,
                    scrollX: true,
                    lengthMenu: [10, 20, 50],
                    pageLength: 10,
                    ordering: true,
                    order: [[0, 'desc']],
                    columnDefs: [{ targets: -1, orderable: false }],
                    dom:
                        '<"card-header dt-head d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3"' +
                        '<"d-flex flex-column flex-sm-row align-items-center justify-content-sm-end gap-3 w-100"f>>' +
                        't' +
                        '<"card-footer d-flex flex-column align-items-center gap-2"' +
                        '<"row w-100 align-items-center g-2"' +
                            '<"col-12 col-md-5 d-flex align-items-center justify-content-md-start justify-content-center gap-2"l i>' +
                            '<"col-12 col-md-7 d-flex justify-content-md-end justify-content-center"p>' +
                        '>>',
                    language: {
                        sLengthMenu: '_MENU_ ',
                        search: '',
                        searchPlaceholder: 'Search Payments',
                        paginate: { next: '<i class="ri-arrow-right-s-line"></i>', previous: '<i class="ri-arrow-left-s-line"></i>' }
                    }
                });
            }
            document.querySelectorAll('.approve-btn').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Konfirmasi Approve',
                        text: 'Apakah Anda yakin menyetujui pembayaran ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Approve',
                        cancelButtonText: 'Batal'
                    }).then(function(result){ if (result.isConfirmed && form) form.submit(); });
                });
            });
            document.querySelectorAll('.reject-btn').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Konfirmasi Reject',
                        text: 'Apakah Anda yakin menolak pembayaran ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Reject',
                        cancelButtonText: 'Batal'
                    }).then(function(result){ if (result.isConfirmed && form) form.submit(); });
                });
            });
        });
    </script>
@endsection