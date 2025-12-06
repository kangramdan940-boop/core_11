@extends('layouts.admin.master')

@section('title', 'Transaksi Emas Ready - Admin')
@section('sub-title', 'Transaksi Ready')
@section('breadcrumbExtra', 'Transaksi Emas Ready')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.ready.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="readyTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
                    <thead class="bg-light bg-opacity-30">
                        <tr>
                            <th>ID</th>
                            <th>Kode Trans</th>
                            <th>Customer</th>
                            <th>Agen</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total (IDR)</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readyTrans as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->kode_trans }}</td>
                                <td>{{ optional($t->customer)->full_name ?? '-' }}</td>
                                <td>{{ optional($t->agen)->name ?? '-' }}</td>
                                <td>{{ optional($t->readyStock)->kode_item ?? '-' }}</td>
                                <td>{{ $t->qty }}</td>
                                <td>{{ number_format((float)$t->total_amount, 2, ',', '.') }}</td>
                                <td>
                                    @php($st = $t->status)
                                    @if($st === 'pending_payment')
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    @elseif($st === 'paid')
                                        <span class="badge bg-success">PAID</span>
                                    @elseif($st === 'waiting_shipment')
                                        <span class="badge bg-info text-dark">WAITING SHIPMENT</span>
                                    @elseif($st === 'shipped')
                                        <span class="badge bg-primary">SHIPPED</span>
                                    @elseif($st === 'completed')
                                        <span class="badge bg-success">COMPLETED</span>
                                    @else
                                        <span class="badge bg-secondary">CANCELLED</span>
                                    @endif
                                </td>
                                <td>{{ optional($t->created_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <a href="{{ route('admin.trans.ready.show', $t) }}" class="btn icon-btn-sm btn-light-primary"><i class="bi bi-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-3">Belum ada transaksi ready.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($readyTrans->hasPages())
                <div class="p-2">
                    {{ $readyTrans->links() }}
                </div>
            @endif
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
            const tableEl = document.getElementById('readyTable');
            if (!tableEl || typeof $ === 'undefined' || !$.fn.DataTable) return;
            const dt = $('#readyTable').DataTable({
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
                    searchPlaceholder: 'Search Ready Transactions',
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });
            const headLabel = document.querySelector('div.head-label');
            if (headLabel) headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Daftar Transaksi Emas Ready</h5>';
            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);
        });
    </script>
@endsection