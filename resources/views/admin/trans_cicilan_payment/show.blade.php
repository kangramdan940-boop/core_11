@extends('layouts.admin.master')

@section('title', 'Detail Pembayaran Cicilan - Admin')
@section('sub-title', 'Transaksi Cicilan')
@section('breadcrumbExtra', 'Detail Pembayaran Cicilan')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan-payments.index'))

@section('content')
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.trans.cicilan-payments.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
        <a href="{{ route('admin.trans.cicilan.show', $payment->kontrak) }}" class="btn btn-sm btn-outline-secondary">Lihat Kontrak</a>
        <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-sm btn-outline-secondary">Payment Logs</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Ringkasan Pembayaran</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>ID</strong><br>{{ $payment->id }}</div>
                <div class="col-md-3"><strong>Kontrak</strong><br>{{ optional($payment->kontrak)->kode_kontrak ?? '-' }}</div>
                <div class="col-md-3"><strong>Cicilan Ke</strong><br>{{ $payment->cicilan_ke }}</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($payment->status) }}</div>

                <div class="col-md-3"><strong>Due Date</strong><br>{{ optional($payment->due_date)->format('Y-m-d') ?? '-' }}</div>
                <div class="col-md-3"><strong>Amount Due</strong><br>{{ number_format((float)$payment->amount_due, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Amount Paid</strong><br>{{ number_format((float)$payment->amount_paid, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Paid At</strong><br>{{ optional($payment->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>

                <div class="col-md-3"><strong>Metode</strong><br>{{ $payment->payment_method ?? '-' }}</div>
                <div class="col-md-3"><strong>Ref</strong><br>{{ $payment->payment_reference ?? '-' }}</div>
                <div class="col-md-12"><strong>Catatan</strong><br>{{ $payment->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Payment Logs Terkait</h6>
            <table id="cicilanPaymentLogsTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableEl = document.getElementById('cicilanPaymentLogsTable');
            if (!tableEl || typeof $ === 'undefined' || !$.fn.DataTable) return;
            const dt = $('#cicilanPaymentLogsTable').DataTable({
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
            const headLabel = document.querySelector('div.head-label');
            if (headLabel) headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Payment Logs Terkait</h5>';
            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);
        });
    </script>
@endsection