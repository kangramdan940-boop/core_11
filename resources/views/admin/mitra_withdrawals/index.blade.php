@extends('layouts.admin.master')

@section('title', 'WD Mitra - Admin')
@section('sub-title', 'Transaksi WD Mitra')
@section('breadcrumbExtra', 'WD Mitra')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.mitra-withdrawals.index'))

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.3.0/css/dataTables.checkboxes.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('admin.trans.mitra-withdrawals.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="filterDate" class="form-label mb-1">Tanggal Dibuat</label>
                    <input type="date" id="filterDate" name="created_date" class="form-control" value="{{ request('created_date') }}">
                </div>
                <div class="col-12 col-md-5">
                    <label for="filterStatus" class="form-label mb-1">Status</label>
                    <select id="filterStatus" name="status" class="form-select">
                        <option value="" {{ (request()->missing('status') || request('status') === '') ? 'selected' : '' }}>Semua</option>
                        <option value="requested" {{ request('status') === 'requested' ? 'selected' : '' }}>Requested</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="checking" {{ request('status') === 'checking' ? 'selected' : '' }}>Checking</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.trans.mitra-withdrawals.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="statusTabs" role="tablist">
        <li class="nav-item"><a href="{{ route('admin.trans.mitra-withdrawals.index') }}" class="nav-link {{ (request()->missing('status') || request('status') === '') ? 'active' : '' }}">Semua</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.mitra-withdrawals.index', ['status' => 'requested']) }}" class="nav-link {{ request('status') === 'requested' ? 'active' : '' }}">Requested</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.mitra-withdrawals.index', ['status' => 'processing']) }}" class="nav-link {{ request('status') === 'processing' ? 'active' : '' }}">Processing</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.mitra-withdrawals.index', ['status' => 'checking']) }}" class="nav-link {{ request('status') === 'checking' ? 'active' : '' }}">Checking</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.mitra-withdrawals.index', ['status' => 'completed']) }}" class="nav-link {{ request('status') === 'completed' ? 'active' : '' }}">Completed</a></li>
        <li class="nav-item"><a href="{{ route('admin.trans.mitra-withdrawals.index', ['status' => 'canceled']) }}" class="nav-link {{ request('status') === 'canceled' ? 'active' : '' }}">Canceled</a></li>
    </ul>

    <div class="card shadow-sm">
        <table id="wdTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th>ID</th>
                    <th>Mitra</th>
                    <th>Amount (IDR)</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th>Processed</th>
                    <th>Completed</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($withdrawals as $w)
                    <tr>
                        <td>{{ $w->id }}</td>
                        <td>{{ optional($w->mitra)->nama_lengkap ?? '-' }}</td>
                        <td>{{ number_format((float)$w->amount, 2, ',', '.') }}</td>
                        <td>
                            @php($st = $w->status)
                            @if($st === 'requested')
                                <span class="badge bg-warning text-dark">REQUESTED</span>
                            @elseif($st === 'processing')
                                <span class="badge bg-info text-dark">PROCESSING</span>
                            @elseif($st === 'checking')
                                <span class="badge bg-primary">CHECKING</span>
                            @elseif($st === 'completed')
                                <span class="badge bg-success">COMPLETED</span>
                            @else
                                <span class="badge bg-secondary">CANCELED</span>
                            @endif
                        </td>
                        <td>{{ optional($w->requested_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($w->processed_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($w->completed_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.trans.mitra-withdrawals.show', $w) }}" class="btn icon-btn-sm btn-light-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;
            const dt = $('#wdTable').DataTable({
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
                    searchPlaceholder: 'Search',
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });

            const headLabel = document.querySelector('div.head-label');
            if (headLabel) {
                headLabel.innerHTML = '<div class="d-flex w-100 align-items-center justify-content-between"><h5 class="card-title text-nowrap mb-0">Daftar WD Mitra</h5></div>';
            }

            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);
        });
    </script>
@endsection