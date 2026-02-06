@extends('layouts.admin.master')

@section('title', 'Tagihan Jatuh Tempo - Admin')
@section('sub-title', 'Transaksi Cicilan')
@section('breadcrumbExtra', 'Tagihan Jatuh Tempo')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan-payments.overdue'))

@section('content')

    <div class="card shadow-sm">
        @php
            $totalOverdue = $payments->sum('amount_due');
            $countPayments = $payments->count();
            $affectedCustomers = $payments->pluck('kontrak.master_customer_id')->filter()->unique()->count();
        @endphp
        <div class="card-body py-2">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="text-muted small">Total Tagihan Jatuh Tempo (IDR)</div>
                    <div class="h5 mb-0">{{ number_format((float)$totalOverdue, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Jumlah Pembayaran Overdue</div>
                    <div class="h5 mb-0">{{ $countPayments }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Jumlah Customer Terdampak</div>
                    <div class="h5 mb-0">{{ $affectedCustomers }}</div>
                </div>
            </div>
        </div>

        <table id="cicilanOverdueTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th>ID</th>
                    <th>Kontrak</th>
                    <th>Customer</th>
                    <th>Cicilan Ke</th>
                    <th>Due Date</th>
                    <th>Hari Terlewat</th>
                    <th>Amount Due</th>
                    <th>Status</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $p)
                    @php
                        $due = optional($p->due_date);
                        $daysLate = $due ? $due->diffInDays(now()) : 0;
                    @endphp
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ optional($p->kontrak)->kode_kontrak ?? '-' }}</td>
                        <td>{{ optional(optional($p->kontrak)->customer)->full_name ?? '-' }}</td>
                        <td>{{ $p->cicilan_ke }}</td>
                        <td>{{ $due ? $due->format('Y-m-d') : '-' }}</td>
                        <td>{{ $daysLate }}</td>
                        <td>{{ number_format((float)$p->amount_due, 2, ',', '.') }}</td>
                        <td>{{ strtoupper($p->status) }}</td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.trans.cicilan-payments.show', $p) }}" class="btn icon-btn-sm btn-light-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.trans.cicilan-payments.notify-overdue', $p) }}" target="_blank" class="btn icon-btn-sm btn-light-success" title="WhatsApp Customer"><i class="bi bi-whatsapp"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        @if (method_exists($payments, 'hasPages') && $payments->hasPages())
            <div class="p-2">
                {{ $payments->links() }}
            </div>
        @endif
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
            const tableEl = document.getElementById('cicilanOverdueTable');
            if (!tableEl || typeof $ === 'undefined' || !$.fn.DataTable) return;
            const dt = $('#cicilanOverdueTable').DataTable({
                responsive: false,
                scrollX: true,
                lengthMenu: [10, 20, 50],
                pageLength: 10,
                ordering: true,
                order: [[5, 'desc']],
                columnDefs: [{ targets: -1, orderable: false }],
                dom:
                    '<\"card-header dt-head d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3\"' +
                    '<\"head-label\">' +
                    '<\"d-flex flex-column flex-sm-row align-items-center justify-content-sm-end gap-3 w-100\"f>>' +
                    't' +
                    '<\"card-footer d-flex flex-column align-items-center gap-2\"' +
                    '<\"row w-100 align-items-center g-2\"' +
                        '<\"col-12 col-md-5 d-flex align-items-center justify-content-md-start justify-content-center gap-2\"l i>' +
                        '<\"col-12 col-md-7 d-flex justify-content-md-end justify-content-center\"p>' +
                    '>>',
                language: {
                    sLengthMenu: '_MENU_ ',
                    search: '',
                    searchPlaceholder: 'Search Overdue',
                    paginate: { next: '<i class="ri-arrow-right-s-line"></i>', previous: '<i class="ri-arrow-left-s-line"></i>' }
                }
            });
            const headLabel = document.querySelector('div.head-label');
            if (headLabel) headLabel.innerHTML = '<h5 class=\"card-title text-nowrap mb-0\">Daftar Tagihan Jatuh Tempo</h5>';
            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);
        });
    </script>
@endsection