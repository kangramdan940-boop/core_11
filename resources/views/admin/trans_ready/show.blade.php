@extends('layouts.admin.master')

@section('title', 'Detail Transaksi Emas Ready - Admin')
@section('sub-title', 'Transaksi Ready')
@section('breadcrumbExtra', 'Detail Transaksi Emas Ready')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.ready.index'))

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trans.ready.index') }}" class="btn btn-secondary">← Kembali</a>
            <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-outline-secondary">Payment Logs</a>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.trans.ready.update-status', $ready) }}" method="POST" class="d-flex align-items-center gap-2">
                @csrf
                <div class="input-group" style="min-width:280px;">
                    <span class="input-group-text"><i class="ri-flag-2-line"></i></span>
                    <select name="status" class="form-select">
                        <option value="pending_payment" @selected($ready->status==='pending_payment')>PENDING_PAYMENT</option>
                        <option value="paid" @selected($ready->status==='paid')>PAID</option>
                        <option value="shipped" @selected($ready->status==='shipped')>SHIPPED</option>
                        <option value="completed" @selected($ready->status==='completed')>COMPLETED</option>
                        <option value="cancelled" @selected($ready->status==='cancelled')>CANCELLED</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update Status</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
 
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Ringkasan Transaksi</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Kode Trans</strong>
                    <div class="mt-1">{{ $ready->kode_trans }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Status</strong>
                    <div class="mt-1">{{ strtoupper($ready->status) }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Qty</strong>
                    <div class="mt-1">{{ $ready->qty }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Total Amount</strong>
                    <div class="mt-1">{{ number_format((float)$ready->total_amount, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Customer</strong>
                    <div class="mt-1">{{ optional($ready->customer)->full_name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Agen</strong>
                    <div class="mt-1">{{ optional($ready->agen)->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Item</strong>
                    <div class="mt-1">{{ optional($ready->readyStock)->kode_item ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Metode Bayar</strong>
                    <div class="mt-1">{{ $ready->payment_method ?? '-' }}</div>
                </div>

                <div class="col-md-3">
                    <strong>Ordered At</strong>
                    <div class="mt-1">{{ optional($ready->ordered_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Paid At</strong><br>
                    <div class="mt-1">{{ optional($ready->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Shipped At</strong><br>
                    <div class="mt-1">{{ optional($ready->shipped_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Completed At</strong><br>
                    <div class="mt-1">{{ optional($ready->completed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>

                <div class="col-md-12"><strong>Catatan</strong><br>{{ $ready->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Data Pengiriman</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Nama</strong>
                    <div class="mt-1">{{ $ready->shipping_name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>WhatsApp</strong>
                    <div class="mt-1">{{ $ready->shipping_phone ?? '-' }}</div>
                </div>
                <div class="col-md-12">
                    <strong>Alamat</strong>
                    <div class="mt-1">{{ $ready->shipping_address ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Kota</strong>
                    <div class="mt-1">{{ $ready->shipping_city ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Provinsi</strong>
                    <div class="mt-1">{{ $ready->shipping_province ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Kode Pos</strong>
                    <div class="mt-1">{{ $ready->shipping_postal_code ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Payment Logs Terkait</h6>
            <table id="readyPaymentLogsTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
                <thead class="bg-light bg-opacity-30">
                    <tr>
                        <th width="10px;">ID</th>
                        <th>Kode Payment</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Metode</th>
                        <th>Provider</th>
                        <th>Paid At</th>
                        <th style="width:120px;">Aksi</th>
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
                                        <button type="submit" class="approve-btn icon-btn-sm btn btn-success"><i class="bi bi-check2"></i></button>
                                    </form>
                                    <form action="{{ route('admin.trans.payment-logs.reject', $l) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="reject-btn icon-btn-sm btn btn-outline-danger"><i class="bi bi-x"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-3">Belum ada payment log terkait.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Log Status Transaksi</h6>
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ strtoupper($log->status) }}</td>
                            <td>{{ $log->description ?? '-' }}</td>
                            <td>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">Belum ada log status.</td>
                        </tr>
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
            const tableEl = document.getElementById('readyPaymentLogsTable');
            if (tableEl && typeof $ !== 'undefined' && $.fn.DataTable) {
                const dt = $('#readyPaymentLogsTable').DataTable({
                    responsive: false,
                    scrollX: true,
                    lengthMenu: [10, 20, 50],
                    pageLength: 10,
                    ordering: true,
                    order: [[0, 'desc']],
                    columnDefs: [{ targets: -1, orderable: false }],
                    dom:
                        '<"card-header dt-head d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3"' +
                        '<"head-label">' +
                        '<"d-flex flex-column flex-sm-row align-items-center justify-content-sm-end gap-3 w-100"f>>' +
                        't' +
                        '<"card-footer d-flex flex-column align-items-center gap-2"' +
                        '<"row w-100 align-items-center g-2"' +
                            '<"col-12 col-md-8 d-flex align-items-center justify-content-md-start justify-content-center gap-2"l i>' +
                            '<"col-12 col-md-4 d-flex justify-content-md-end justify-content-center"p>' +
                        '>>',
                    language: {
                        sLengthMenu: '_MENU_ ',
                        search: '',
                        searchPlaceholder: 'Search Payments',
                        paginate: {
                            next: '<i class="ri-arrow-right-s-line"></i>',
                            previous: '<i class="ri-arrow-left-s-line"></i>'
                        }
                    }
                });
                setTimeout(function () {
                    const filterInput = document.querySelector('.dataTables_filter .form-control');
                    const lengthSelect = document.querySelector('.dataTables_length .form-select');
                    if (filterInput) filterInput.classList.remove('form-control-sm');
                    if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
                }, 300);
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