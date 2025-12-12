@extends('layouts.admin.master')

@section('title', 'Payment Logs - Admin')
@section('sub-title', 'Transaksi')
@section('breadcrumbExtra', 'Payment Logs')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.payment-logs.index'))

@section('content')
    <div class="card shadow-sm">
        <table id="paymentLogsTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th>ID</th>
                    <th>Kode Payment</th>
                    <th>Ref</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Provider</th>
                    <th>Dibuat</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $l)
                    <tr>
                        <td>{{ $l->id }}</td>
                        <td>{{ $l->kode_payment }}</td>
                        <td>{{ strtoupper($l->ref_type) }}#{{ $l->ref_id ?? '-' }}</td>
                        <td>
                            @php($st = $l->status)
                            @if($st === 'paid')
                                <span class="badge bg-success">PAID</span>
                            @elseif($st === 'pending')
                                <span class="badge bg-warning text-dark">PENDING</span>
                            @elseif($st === 'failed')
                                <span class="badge bg-danger">FAILED</span>
                            @elseif($st === 'expired')
                                <span class="badge bg-secondary">EXPIRED</span>
                            @else
                                <span class="badge bg-info">REFUNDED</span>
                            @endif
                        </td>
                        <td>{{ number_format((float)$l->amount, 2, ',', '.') }} {{ $l->currency }}</td>
                        <td>{{ $l->payment_method ?? '-' }}</td>
                        <td>{{ $l->provider ?? '-' }}</td>
                        <td>{{ optional($l->created_at)->format('Y-m-d H:i') }}</td>
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
            const tableEl = document.getElementById('paymentLogsTable');
            if (!tableEl || typeof $ === 'undefined' || !$.fn.DataTable) return;
            const dt = $('#paymentLogsTable').DataTable({
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
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });
            const headLabel = document.querySelector('div.head-label');
            if (headLabel) headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Payment Logs</h5>';
            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);
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