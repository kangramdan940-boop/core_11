@extends('layouts.admin.master')

@section('title', 'Transaksi Buyback - Admin')
@section('sub-title', 'Transaksi Buyback')
@section('breadcrumbExtra', 'Transaksi Buyback')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.buyback.index'))

@section('content')
    <div class="card shadow-sm">
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['date' => 'today']) }}" class="nav-link {{ request('date') === 'today' ? 'active' : '' }}">Hari Ini <span class="badge rounded-pill text-bg-secondary ms-2">{{ $todayCount ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index') }}" class="nav-link {{ (request()->missing('status') || request('status') === '') && (request()->missing('date') || request('date') === '') && (request()->missing('created_date') || request('created_date') === '') ? 'active' : '' }}">Semua <span class="badge rounded-pill text-bg-secondary ms-2">{{ $totalCount ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['status' => 'pending_review']) }}" class="nav-link {{ request('status') === 'pending_review' ? 'active' : '' }}">Menunggu Verifikasi <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['pending_review'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['status' => 'priced']) }}" class="nav-link {{ request('status') === 'priced' ? 'active' : '' }}">Priced <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['priced'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['status' => 'approved']) }}" class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}">Disetujui <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['approved'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['status' => 'paid']) }}" class="nav-link {{ request('status') === 'paid' ? 'active' : '' }}">Paid <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['paid'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['status' => 'completed']) }}" class="nav-link {{ request('status') === 'completed' ? 'active' : '' }}">Completed <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['completed'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['status' => 'rejected']) }}" class="nav-link {{ request('status') === 'rejected' ? 'active' : '' }}">Rejected <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['rejected'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.buyback.index', ['status' => 'cancelled']) }}" class="nav-link {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['cancelled'] ?? 0 }}</span></a></li>
        </ul>
        <table id="buybackTransTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th style="width:64px;">ID</th>
                    <th style="width:180px;">Kode Trans</th>
                    <th style="min-width:180px;">Customer</th>
                    <th style="min-width:140px;">WA/Tlp</th>
                    <th style="min-width:160px;">Emas</th>
                    <th class="text-end" style="width:80px;">Qty</th>
                    <th class="text-end" style="min-width:150px;">Total (IDR)</th>
                    <th class="text-center" style="width:160px;">Status</th>
                    <th style="min-width:150px;">Dibuat</th>
                    <th class="text-end" style="width:90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($buybackTrans as $t)
                    @php
                        $badgeMap = [
                            'pending_review' => 'bg-warning text-dark',
                            'priced'         => 'bg-info text-dark',
                            'approved'       => 'bg-primary',
                            'paid'           => 'bg-success',
                            'completed'      => 'bg-success',
                            'rejected'       => 'bg-danger',
                            'cancelled'      => 'bg-secondary',
                        ];
                        $waRaw = optional($t->customer)->phone_wa;
                        $waDigits = $waRaw ? preg_replace('/\D+/', '', $waRaw) : null;
                        if ($waDigits && substr($waDigits, 0, 1) === '0') { $waDigits = '62' . substr($waDigits, 1); }
                    @endphp
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->kode_trans }}</td>
                        <td>{{ optional($t->customer)->full_name ?? '-' }}</td>
                        <td>
                            @if(!empty($waDigits))
                                <a href="https://wa.me/{{ $waDigits }}" target="_blank" class="btn btn-sm btn-success">WA</a>
                                <div class="small text-muted mt-1">{{ $waDigits }}</div>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $t->brand ?? '-' }} • {{ number_format((float)$t->berat_gram, 3, ',', '.') }} g</td>
                        <td class="text-end">{{ $t->qty }}</td>
                        <td class="text-end">{{ number_format((float)$t->total_amount, 2, ',', '.') }}</td>
                        <td class="text-center"><span class="badge {{ $badgeMap[$t->status] ?? 'bg-secondary' }}">{{ strtoupper(str_replace('_',' ',$t->status)) }}</span></td>
                        <td>{{ optional($t->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.trans.buyback.show', $t->id) }}" class="btn icon-btn-sm btn-light-primary"><i class="bi bi-eye"></i></a>
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
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableEl = document.getElementById('buybackTransTable');
            if (!tableEl || typeof $ === 'undefined' || !$.fn.DataTable) return;
            $('#buybackTransTable').DataTable({
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
                    searchPlaceholder: 'Search Buyback',
                    paginate: { next: '<i class="ri-arrow-right-s-line"></i>', previous: '<i class="ri-arrow-left-s-line"></i>' }
                }
            });
            const headLabel = document.querySelector('div.head-label');
            if (headLabel) headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Daftar Transaksi Buyback</h5>';
            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);
        });
    </script>
@endsection
