@extends('layouts.admin.master')

@section('title', 'Transaksi Emas Ready - Admin')
@section('sub-title', 'Transaksi Ready')
@section('breadcrumbExtra', 'Transaksi Emas Ready')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.ready.index'))

@section('content')
    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('admin.trans.ready.invoice.bulk.pdf', ['status' => 'shipped']) }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">Print Semua Invoice (Shipped)</a>
        <button type="button" id="printSelectedReadyInvoicesBtn" class="btn btn-outline-primary btn-sm me-2">Print Invoice Terpilih</button>
        <form id="cancelPendingReadyForm" action="{{ route('admin.trans.ready.cancel-pending-all') }}" method="POST" class="d-inline">
            @csrf
            <button type="button" id="cancelPendingReadyBtn" class="btn btn-outline-danger btn-sm">Batalkan Semua Pending</button>
        </form>
    </div>
    <div class="card shadow-sm">
        <ul class="nav nav-tabs mb-3" id="readyStatusTabs" role="tablist">
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index', ['date' => 'today']) }}" class="nav-link {{ request('date') === 'today' ? 'active' : '' }}">Hari Ini <span class="badge rounded-pill text-bg-secondary ms-2">{{ $todayCount ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index') }}" class="nav-link {{ (request()->missing('status') || request('status') === '') && (request()->missing('date') || request('date') === '') && (request()->missing('created_date') || request('created_date') === '') ? 'active' : '' }}">Semua <span class="badge rounded-pill text-bg-secondary ms-2">{{ $totalCount ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index', ['status' => 'pending_payment']) }}" class="nav-link {{ request('status') === 'pending_payment' ? 'active' : '' }}">Pending <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['pending_payment'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index', ['status' => 'paid']) }}" class="nav-link {{ request('status') === 'paid' ? 'active' : '' }}">Paid <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['paid'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index', ['status' => 'waiting_shipment']) }}" class="nav-link {{ request('status') === 'waiting_shipment' ? 'active' : '' }}">Waiting Shipment <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['waiting_shipment'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index', ['status' => 'shipped']) }}" class="nav-link {{ request('status') === 'shipped' ? 'active' : '' }}">Shipped <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['shipped'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index', ['status' => 'completed']) }}" class="nav-link {{ request('status') === 'completed' ? 'active' : '' }}">Completed <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['completed'] ?? 0 }}</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.ready.index', ['status' => 'cancelled']) }}" class="nav-link {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled <span class="badge rounded-pill text-bg-secondary ms-2">{{ ($statusCounts ?? [])['cancelled'] ?? 0 }}</span></a></li>
        </ul>
        <table id="readyTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th class="text-center" style="width:64px;">ID <input type="checkbox" id="selectAllReadyCheckbox" class="form-check-input ms-2"></th>
                    <th class="text-center" style="width:160px;">Kode Trans</th>
                    <th class="text-center" style="width:120px;">ID Keranjang</th>
                    <th style="min-width:200px;">Customer</th>
                    <th style="min-width:140px;">WA/Tlp</th>
                    <th style="min-width:160px;">Agen</th>
                    <th style="min-width:160px;">Item</th>
                    <th class="text-end" style="width:80px;">Qty</th>
                    <th class="text-end" style="min-width:160px;">Total (IDR)</th>
                    <th class="text-center" style="width:120px;">Status</th>
                    <th style="min-width:160px;">Dibuat</th>
                    <th class="text-end text-nowrap" style="width:160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($readyTrans as $t)
                    <tr>
                        <td>
                            <div>{{ $t->id }}</div>
                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input ready-select-checkbox" value="{{ $t->id }}">
                            </div>
                        </td>
                        <td>{{ $t->kode_trans }}</td>
                        <td>
                            @if($t->id_keranjang)
                                <a href="{{ route('admin.trans.keranjang.show', $t->id_keranjang) }}" class="text-decoration-none">{{ $t->id_keranjang }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ optional($t->customer)->full_name ?? '-' }}</td>
                        <td>
                            @php
                                $waRaw = optional($t->customer)->phone_wa;
                                $waDigits = $waRaw ? preg_replace('/\D+/', '', $waRaw) : null;
                                if ($waDigits && substr($waDigits, 0, 1) === '0') {
                                    $waDigits = '62' . substr($waDigits, 1);
                                }
                                $custName = optional($t->customer)->full_name ?? '';
                                $msg = "Assalamu’alaikum Kak {$custName}\n\nEmas untuk investasi nya sedang di kemas nih ka.\n\nMohon konfirmasi alamat pengiriman berikut:\nKode Transaksi : {$t->kode_trans}\nNama Penerima: -\nNo. HP: -\nAlamat Lengkap: -\nKota: -\nProvinsi: -\nKode Pos: -\ncek ongkos kirim disini : https://jne.co.id/shipping-fee?origin=BKI10000&destination=SUB10000&weight=1\n(pilih tujuan adalah kecamatan nya kamu)\nMohon bantuannya untuk pembayaran ongkir. Terima kasih\nTim jajanemas.com";
                                $waUrl = $waDigits ? ('https://wa.me/' . $waDigits . '?text=' . rawurlencode($msg)) : null;
                            @endphp
                            @if(!empty($waDigits))
                                <a href="{{ $waUrl }}" target="_blank" class="btn btn-sm btn-success">WA</a>
                                <div class="small text-muted mt-1">{{ $waDigits }}</div>
                            @else
                                -
                            @endif
                        </td>
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
                        '<"col-12 col-md-5 d-flex align-items-center justify-content-md-start justify-content-center gap-2"l i>' +
                        '<"col-12 col-md-7 d-flex justify-content-md-end justify-content-center"p>' +
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

            const cancelBtn = document.getElementById('cancelPendingReadyBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    const confirmAction = function () {
                        const form = document.getElementById('cancelPendingReadyForm');
                        if (form) form.submit();
                    };
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: 'Yakin ingin membatalkan semua transaksi PENDING?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, batalkan',
                            cancelButtonText: 'Batal'
                        }).then(function (result) {
                            if (result.isConfirmed) confirmAction();
                        });
                    } else {
                        if (confirm('Yakin ingin membatalkan semua transaksi PENDING?')) confirmAction();
                    }
                });
            }

            const printSelectedBtn = document.getElementById('printSelectedReadyInvoicesBtn');
            if (printSelectedBtn) {
                printSelectedBtn.addEventListener('click', function () {
                    var dt = $('#readyTable').DataTable();
                    var nodes = dt.rows().nodes().toArray();
                    var ids = [];
                    nodes.forEach(function (r) {
                        var cb = r.querySelector('input.ready-select-checkbox');
                        if (cb && cb.checked) ids.push(cb.value);
                    });
                    if (ids.length === 0) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon:'warning', title:'Pilih data', text:'Checklist minimal satu transaksi untuk dicetak.' });
                        }
                        return;
                    }
                    var base = "{{ route('admin.trans.ready.invoice.bulk.pdf') }}";
                    var params = new URLSearchParams();
                    ids.forEach(function (id) { params.append('ids[]', id); });
                    window.open(base + '?' + params.toString(), '_blank', 'noopener');
                });
            }

            const selectAllCb = document.getElementById('selectAllReadyCheckbox');
            if (selectAllCb) {
                selectAllCb.addEventListener('change', function () {
                    var dt = $('#readyTable').DataTable();
                    var nodes = dt.rows().nodes().toArray();
                    nodes.forEach(function (r) {
                        var cb = r.querySelector('input.ready-select-checkbox');
                        if (cb) cb.checked = selectAllCb.checked;
                    });
                });
            }
        });
    </script>
@endsection