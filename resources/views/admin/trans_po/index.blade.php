@extends('layouts.admin.master')

@section('title', 'PO Emas - Admin')
@section('sub-title', 'Transaksi PO')
@section('breadcrumbExtra', 'PO Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.po.index'))

@section('content')
    <div class="card shadow-sm">
        <ul class="nav nav-tabs mb-3" id="statusTabs" role="tablist">
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['date' => 'today']) }}" class="nav-link {{ request('date') === 'today' ? 'active' : '' }}">Hari Ini</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index') }}" class="nav-link {{ (request()->missing('status') || request('status') === '') && (request()->missing('date') || request('date') === '') ? 'active' : '' }}">Semua</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['status' => 'pending_payment']) }}" class="nav-link {{ request('status') === 'pending_payment' ? 'active' : '' }}">Pending</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['status' => 'paid']) }}" class="nav-link {{ request('status') === 'paid' ? 'active' : '' }}">Paid</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['status' => 'processing']) }}" class="nav-link {{ request('status') === 'processing' ? 'active' : '' }}">Processing</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['status' => 'ready_at_agen']) }}" class="nav-link {{ request('status') === 'ready_at_agen' ? 'active' : '' }}">Ready @Agen</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['status' => 'shipped']) }}" class="nav-link {{ request('status') === 'shipped' ? 'active' : '' }}">Shipped</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['status' => 'completed']) }}" class="nav-link {{ request('status') === 'completed' ? 'active' : '' }}">Completed</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['status' => 'cancelled']) }}" class="nav-link {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled</a></li>
        </ul>
        <table id="poTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th width="10px;">No</th>
                    <th>Kode PO</th>
                    <th>Customer</th>
                    <th>Agen</th>
                    <th>Total (IDR)</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pos as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->kode_po }}</td>
                        <td>{{ optional($p->customer)->full_name ?? '-' }}</td>
                        <td>{{ optional($p->agen)->name ?? '-' }}</td>
                        <td>{{ number_format((float)$p->total_amount, 2, ',', '.') }}</td>
                        <td>
                            @php($st = $p->status)
                            @if($st === 'pending_payment')
                                <span class="badge bg-warning text-dark">PENDING</span>
                            @elseif($st === 'paid')
                                <span class="badge bg-success">PAID</span>
                            @elseif($st === 'processing')
                                <span class="badge bg-info text-dark">PROCESSING</span>
                            @elseif($st === 'ready_at_agen')
                                <span class="badge bg-primary">READY @AGEN</span>
                            @elseif($st === 'shipped')
                                <span class="badge bg-primary">SHIPPED</span>
                            @elseif($st === 'completed')
                                <span class="badge bg-success">COMPLETED</span>
                            @else
                                <span class="badge bg-secondary">CANCELLED</span>
                            @endif
                        </td>
                        <td>{{ optional($p->created_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.trans.po.show', $p) }}" class="btn icon-btn-sm btn-light-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (!empty($p->wa_url))
                                    <a href="{{ $p->wa_url }}" target="_blank" rel="noopener" class="btn icon-btn-sm btn-light-success" title="WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
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
            const tableEl = document.getElementById('poTable');
            if (!tableEl) return;
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;

            const dt = $('#poTable').DataTable({
                responsive: false,
                scrollX: true,
                lengthMenu: [10, 20, 50],
                pageLength: 10,
                ordering: true,
                order: [[0, 'asc']],
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
                    searchPlaceholder: 'Search Files',
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });

            const headLabel = document.querySelector('div.head-label');
            if (headLabel) {
                headLabel.innerHTML = '<div class="d-flex w-100 align-items-center justify-content-between"><h5 class="card-title text-nowrap mb-0">Daftar PO Emas</h5><form id="cancelPendingForm" action="{{ route('admin.trans.po.cancel-pending-all') }}" method="POST">@csrf<button type="button" id="cancelPendingBtn" class="btn btn-outline-danger btn-sm">Batalkan Semua Pending</button></form></div>';
            }

            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);

            const cancelBtn = document.getElementById('cancelPendingBtn');
            if (cancelBtn && typeof Swal !== 'undefined') {
                cancelBtn.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Yakin ingin membatalkan semua transaksi PENDING?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, batalkan',
                        cancelButtonText: 'Batal'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            const form = document.getElementById('cancelPendingForm');
                            if (form) form.submit();
                        }
                    });
                });
            }
        });
    </script>
@endsection