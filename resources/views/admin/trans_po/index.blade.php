@extends('layouts.admin.master')

@section('title', 'PO Emas - Admin')
@section('sub-title', 'Transaksi PO')
@section('breadcrumbExtra', 'PO Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.po.index'))

@section('content')
    @section('content')
    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#fifoCalculatorModal">Kalkulator FIFO</button>
        <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#kepingCalculatorModal">Kalkulator Keping</button>
        <form id="cancelPendingForm" action="{{ route('admin.trans.po.cancel-pending-all') }}" method="POST">
            @csrf
            <button type="button" id="cancelPendingBtn" class="btn btn-outline-danger btn-sm">Batalkan Semua Pending</button>
        </form>
    </div>
    <div class="modal fade" id="fifoCalculatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kalkulator Stok & Prioritas FIFO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1">Stok tersedia (gram)</label>
                            <input type="number" min="0" step="1" id="fifoStockInput" class="form-control" placeholder="Masukkan stok gram">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label mb-1">Status yang diprioritaskan</label>
                            <select id="fifoStatusSelect" class="form-select" multiple>
                                <option value="paid" selected>PAID</option>
                                <option value="processing" selected>PROCESSING</option>
                                <option value="ready_at_agen" selected>READY @AGEN</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-end">
                            <button type="button" id="fifoCalculateBtn" class="btn btn-primary w-100">Hitung</button>
                        </div>
                    </div>
                    <div class="mt-3" id="fifoResultContainer"></div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted">Metode: FIFO berdasarkan tanggal dibuat paling awal.</small>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kepingCalculatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kalkulator Kebutuhan Keping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12 col-md-5">
                            <label class="form-label mb-1">Status yang dipakai</label>
                            <select id="kepingStatusSelect" class="form-select" multiple>
                                <option value="paid" selected>PAID</option>
                                <option value="processing" selected>PROCESSING</option>
                                <option value="ready_at_agen" selected>READY @AGEN</option>
                            </select>
                        </div>
                      
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1">Tanggal Mulai (opsional)</label>
                            <input type="date" id="kepingDateStart" class="form-control">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1">Tanggal Akhir (opsional)</label>
                            <input type="date" id="kepingDateEnd" class="form-control">
                        </div>
                          <div class="col-12 col-md-3 d-flex align-items-end">
                            <button type="button" id="kepingCalculateBtn" class="btn btn-primary w-100">Hitung</button>
                        </div>
                    </div>

                    <div class="mt-3" id="kepingResultContainer"></div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted">Hitung keping berdasarkan gramasi unik dari data.</small>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('admin.trans.po.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="filterDate" class="form-label mb-1">Tanggal Dibuat</label>
                    <input type="date" id="filterDate" name="created_date" class="form-control" value="{{ request('created_date') ?? (request('date') === 'today' ? now()->format('Y-m-d') : '') }}">
                </div>
                <div class="col-12 col-md-5">
                    <label for="filterStatus" class="form-label mb-1">Status</label>
                    <select id="filterStatus" name="status" class="form-select">
                        <option value="" {{ (request()->missing('status') || request('status') === '') ? 'selected' : '' }}>Semua</option>
                        <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="ready_at_agen" {{ request('status') === 'ready_at_agen' ? 'selected' : '' }}>Ready @Agen</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.trans.po.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <ul class="nav nav-tabs mb-3" id="statusTabs" role="tablist">
            <li class="nav-item"><a href="{{ route('admin.trans.po.index', ['date' => 'today']) }}" class="nav-link {{ request('date') === 'today' ? 'active' : '' }}">Hari Ini</a></li>
            <li class="nav-item"><a href="{{ route('admin.trans.po.index') }}" class="nav-link {{ (request()->missing('status') || request('status') === '') && (request()->missing('date') || request('date') === '') && (request()->missing('created_date') || request('created_date') === '') ? 'active' : '' }}">Semua</a></li>
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
                    <th>Nama / Telepon</th>
                    <th>Gramasi (Qty)</th>
                    <th>Keranjang</th>
                    <th>Total Gram</th>
                    <th>Biaya Pengiriman</th>
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
                        <td>
                            {{ optional($p->customer)->full_name ?? '-' }}
                            <div class="text-muted small">{{ optional($p->customer)->phone_wa ?? '-' }}</div>
                        </td>
                        <td>{{ number_format((float)(optional(optional($p->produk)->gramasi)->gramasi ?? 0), 3, ',', '.') }} Gram x ({{ (int)($p->qty ?? 0) }} Keping)</td>
                        <td>
                            @if (!empty($p->id_keranjang))
                                <a href="{{ route('admin.trans.po.index', ['keranjang_id' => $p->id_keranjang]) }}" class="text-primary text-decoration-underline">
                                    {{ optional($p->keranjang)->kode_keranjang ?? $p->id_keranjang }}
                                </a>
                                <span class="badge {{ (optional($p->keranjang)->status_kadaluarsa === 'expired') ? 'bg-secondary' : 'bg-success' }} ms-1">
                                    {{ strtoupper(optional($p->keranjang)->status_kadaluarsa ?? '-') }}
                                </span>
                                <div class="text-muted small">Kadaluarsa: {{ optional(optional($p->keranjang)->expires_at)->format('Y-m-d H:i') ?? '-' }}</div>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ (int)($p->qty * $p->total_gram ) }} Gram</td>
                        <td>{!! ((float)($p->shipping_cost ?? 0)) > 0 ? number_format((float)($p->shipping_cost ?? 0), 2, ',', '.') : '<span class="badge bg-danger">Follow Up Ongkir</span>' !!}</td>
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
                                @if ($p->status === 'paid' && !empty(optional($p->customer)->email))
                                    <form action="{{ route('admin.trans.po.send-email', $p) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn icon-btn-sm btn-light-warning" title="Kirim Email">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    </form>
                                @endif
                                @if ($p->status === 'shipped' && !empty(optional($p->customer)->email))
                                    <form action="{{ route('admin.trans.po.send-shipping-email', $p) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn icon-btn-sm btn-light-warning" title="Kirim Email Pengiriman">
                                            <i class="bi bi-envelope"></i>
                                        </button>
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
                headLabel.innerHTML = '<div class="d-flex w-100 align-items-center justify-content-between"><h5 class="card-title text-nowrap mb-0">Daftar PO Emas</h5></div>';
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

            function getRowsData() {
                var dt = $('#poTable').DataTable();
                var nodes = dt.rows().nodes().toArray();
                return nodes.map(function (r) {
                    var tds = r.querySelectorAll('td');
                    var kode = (tds[1] && tds[1].textContent || '').trim();
                    var customerCell = tds[2];
                    var nama = '';
                    var telepon = '';
                    if (customerCell) {
                        nama = (customerCell.childNodes[0]?.textContent || '').trim();
                        var phoneDiv = customerCell.querySelector('div.small');
                        telepon = (phoneDiv?.textContent || '').trim();
                    }
                    var gram = parseInt(((tds[4] && tds[4].textContent) || '').replace(/\D+/g, ''), 10) || 0;
                    var statusText = ((tds[7] && tds[7].textContent) || '').trim().toLowerCase();
                    var createdText = (tds[8] && tds[8].textContent || '').trim();
                    var status = 'cancelled';
                    if (statusText.indexOf('pending') >= 0) status = 'pending_payment';
                    else if (statusText.indexOf('paid') >= 0) status = 'paid';
                    else if (statusText.indexOf('process') >= 0) status = 'processing';
                    else if (statusText.indexOf('ready') >= 0) status = 'ready_at_agen';
                    else if (statusText.indexOf('shipp') >= 0) status = 'shipped';
                    else if (statusText.indexOf('complet') >= 0) status = 'completed';
                    var createdAt = new Date(createdText.replace(' ', 'T'));
                    return { kode: kode, nama: nama, telepon: telepon, gram: gram, status: status, createdAt: createdAt, createdText: createdText };
                });
            }

            function normalizeWaPhone(raw) {
                var digits = String(raw || '').replace(/\D+/g, '');
                if (digits.indexOf('0') === 0) digits = '62' + digits.slice(1);
                return digits;
            }
            function buildWaMessage(item) {
                var nama = (item && item.nama ? String(item.nama).trim() : '');
                var sapaan = nama !== '' ? ('Kak ' + nama) : 'Kak';
                var kode = (item && item.kode ? item.kode : '-');
                var gram = (item && item.gram ? item.gram : 0);
                return 'Assalamu’alaikum ' + sapaan + ' 🙏\n\n' +
                    'Informasi: Pesanan emas Anda (Kode PO: ' + kode + ', ' + gram + ' gram) akan segera kami kirim sesuai prioritas FIFO.\n' +
                    'Mohon bantuannya untuk share location via WhatsApp dan melengkapi alamat pengiriman dengan format berikut:\n\n' +
                    'Nama Penerima:\n' +
                    'No. HP:\n' +
                    'Alamat Lengkap:\n' +
                    'Kota:\n' +
                    'Provinsi:\n' +
                    'Kode Pos:\n\n' +
                    'Terima kasih 🙏\nTim jajanemas.com';
            }

            function renderFifoResult(items, stokAwal) {
                var sisa = stokAwal;
                var selected = [];
                for (var i = 0; i < items.length; i++) {
                    var it = items[i];
                    if (it.gram <= sisa) {
                        selected.push(it);
                        sisa -= it.gram;
                    } else {
                        break;
                    }
                }
                var used = stokAwal - sisa;
                var html = '';
                html += '<div class="mb-2">Stok awal: <strong>' + stokAwal + ' gram</strong> · Terpakai: <strong>' + used + ' gram</strong> · Sisa: <strong>' + sisa + ' gram</strong></div>';
                html += '<table class="table table-sm table-bordered"><thead><tr><th>Kode PO</th><th>Nama / Telepon</th><th>Status</th><th>Dibuat</th><th>Total Gram</th><th>Akumulasi</th></tr></thead><tbody>';
                var acc = 0;
                for (var j = 0; j < selected.length; j++) {
                    acc += selected[j].gram;
                    var waDigits = normalizeWaPhone(selected[j].telepon || '');
                    var waLink = waDigits ? ('<a href="https://wa.me/' + waDigits + '?text=' + encodeURIComponent(buildWaMessage(selected[j])) + '" target="_blank" rel="noopener" class="text-decoration-none">' + (selected[j].telepon || '-') + '</a>') : (selected[j].telepon || '-');
                    html += '<tr><td>' + selected[j].kode + '</td><td>' + (selected[j].nama || '-') + '<div class="text-muted small">' + waLink + '</div></td><td>' + selected[j].status + '</td><td>' + selected[j].createdText + '</td><td>' + selected[j].gram + '</td><td>' + acc + '</td></tr>';
                }
                html += '</tbody></table>';
                if (selected.length === 0) {
                    html = '<div class="alert alert-warning">Tidak ada PO yang masuk dalam batas stok.</div>';
                }
                var container = document.getElementById('fifoResultContainer');
                if (container) container.innerHTML = html;
            }

            const fifoCalculateBtn = document.getElementById('fifoCalculateBtn');
            if (fifoCalculateBtn) {
                fifoCalculateBtn.addEventListener('click', function () {
                    var stok = parseInt((document.getElementById('fifoStockInput')?.value || '0'), 10) || 0;
                    var select = document.getElementById('fifoStatusSelect');
                    var selectedStatuses = select ? Array.from(select.selectedOptions).map(function (o) { return o.value; }) : ['paid', 'processing', 'ready_at_agen'];
                    var items = getRowsData().filter(function (it) { return selectedStatuses.indexOf(it.status) >= 0; });
                    items.sort(function (a, b) {
                        var ta = a.createdAt.getTime();
                        var tb = b.createdAt.getTime();
                        if (isNaN(ta) && isNaN(tb)) return 0;
                        if (isNaN(ta)) return 1;
                        if (isNaN(tb)) return -1;
                        return ta - tb;
                    });
                    renderFifoResult(items, stok);
                });
            }

            function aggregateByGramasi(statuses, startDate, endDate) {
                var dt = $('#poTable').DataTable();
                var nodes = dt.rows().nodes().toArray();
                var map = new Map();
                var totalKepingAll = 0;
                var totalGramAll = 0;
                nodes.forEach(function (r) {
                    var tds = r.querySelectorAll('td');
                    var statusText = ((tds[7] && tds[7].textContent) || '').trim().toLowerCase();
                    var status = 'cancelled';
                    if (statusText.indexOf('pending') >= 0) status = 'pending_payment';
                    else if (statusText.indexOf('paid') >= 0) status = 'paid';
                    else if (statusText.indexOf('process') >= 0) status = 'processing';
                    else if (statusText.indexOf('ready') >= 0) status = 'ready_at_agen';
                    else if (statusText.indexOf('shipp') >= 0) status = 'shipped';
                    else if (statusText.indexOf('complet') >= 0) status = 'completed';
                    if (statuses.indexOf(status) < 0) return;
                    var createdText = ((tds[8] && tds[8].textContent) || '').trim();
                    var d = String(createdText).slice(0, 10);
                    if (startDate || endDate) {
                        if (startDate && endDate && !(d >= startDate && d <= endDate)) return;
                        if (startDate && !(d >= startDate)) return;
                        if (endDate && !(d <= endDate)) return;
                    }
                    var cellText = (tds[3] && tds[3].textContent) || '';
                    var mGramasi = cellText.match(/([\d\.\,]+)\s*Gram/i);
                    var mKeping = cellText.match(/\(\s*(\d+)\s*Keping\s*\)/i);
                    var g = 0;
                    var k = 0;
                    if (mGramasi) g = parseInt(String(mGramasi[1]).replace(/\D+/g, ''), 10) || 0;
                    if (mKeping) k = parseInt(String(mKeping[1]).replace(/\D+/g, ''), 10) || 0;
                    if (g <= 0 || k <= 0) return;
                    var totalGRow = g * k;
                    totalKepingAll += k;
                    totalGramAll += totalGRow;
                    var prev = map.get(g) || { gramasi: g, totalKeping: 0, totalGram: 0 };
                    prev.totalKeping += k;
                    prev.totalGram += totalGRow;
                    map.set(g, prev);
                });
                var list = Array.from(map.values()).sort(function (a, b) { return a.gramasi - b.gramasi; });
                return { list: list, totalKepingAll: totalKepingAll, totalGramAll: totalGramAll };
            }

            function renderKepingAggregates(agg) {
                var html = '';
                html += '<table class="table table-sm table-bordered"><thead><tr><th>Gramasi</th><th>Total Keping</th><th>Total Gram</th></tr></thead><tbody>';
                agg.list.forEach(function (row) {
                    html += '<tr><td>' + row.gramasi + ' g</td><td>' + row.totalKeping + '</td><td>' + row.totalGram + ' g</td></tr>';
                });
                html += '</tbody></table>';
                html += '<div class="mt-2"><strong>Total keping:</strong> ' + agg.totalKepingAll + ' · <strong>Total gram:</strong> ' + agg.totalGramAll + ' g</div>';
                var container = document.getElementById('kepingResultContainer');
                if (container) container.innerHTML = html;
            }

            const kepingCalculateBtn = document.getElementById('kepingCalculateBtn');
            if (kepingCalculateBtn) {
                kepingCalculateBtn.addEventListener('click', function () {
                    var select = document.getElementById('kepingStatusSelect');
                    var statuses = select ? Array.from(select.selectedOptions).map(function (o) { return o.value; }) : ['paid','processing','ready_at_agen'];
                    var startDate = (document.getElementById('kepingDateStart')?.value || '').trim();
                    var endDate = (document.getElementById('kepingDateEnd')?.value || '').trim();
                    var agg = aggregateByGramasi(statuses, startDate, endDate);
                    renderKepingAggregates(agg);
                });
            }
        });
    </script>
@endsection