@extends('layouts.admin.master')

@section('title', 'Master Stok Emas Ready - Admin')
@section('sub-title', 'Stok Emas Ready')
@section('breadcrumbExtra', 'Master Stok Emas Ready')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.ready-stocks.index'))

@section('content')

    <div class="card shadow-sm">
        <table id="goldReadyTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th width="10px;">ID</th>
                    <th>Kode Item</th>
                    <th>Brand</th>
                    <th>Gramasi (g)</th>
                    <th>Status</th>
                    <th>Stok</th>
                    <th>Aktif</th>
                    <th>Harga Jual Fix</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stocks as $s)
                    <tr>
                        <td class="text-center">{{ $s->id }}</td>
                        <td class="text-center"><span class="badge bg-light text-dark border text-uppercase">{{ $s->kode_item }}</span></td>
                        <td class="text-uppercase">{{ $s->brand }}</td>
                        <td class="text-end">{{ number_format((float)$s->gramasi, 3, ',', '.') }}</td>
                        <td class="text-center">
                            @if($s->status === 'available')
                                <span class="badge rounded-pill bg-success">Available</span>
                            @elseif($s->status === 'reserved')
                                <span class="badge rounded-pill bg-warning text-dark">Reserved</span>
                            @else
                                <span class="badge rounded-pill bg-secondary">Sold</span>
                            @endif
                        </td>
                        <td class="text-end">{{ (int) ($s->stok ?? 0) }}</td>
                        <td>
                            @if($s->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="harga-jual-fix-cell" data-id="{{ $s->id }}" data-value="{{ $s->harga_jual_fix !== null ? (float)$s->harga_jual_fix : '' }}">
                            <span class="harga-jual-fix-display fs-5 fw-semibold">{{ $s->harga_jual_fix !== null ? 'Rp ' . number_format((float)$s->harga_jual_fix, 0, ',', '.') : '-' }}</span>
                        </td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.master.ready-stocks.edit', $s) }}" class="btn icon-btn-sm btn-light-primary">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="#" class="btn icon-btn-sm btn-light-danger delete-item"
                                    data-action="{{ route('admin.master.ready-stocks.destroy', $s) }}"
                                    data-label="{{ $s->kode_item ? $s->kode_item : ('#' . $s->id) }}">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.master.ready-stocks.destroy', $s) }}" method="POST" class="d-none delete-form">
                                @csrf
                                @method('DELETE')
                            </form>
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
    <style>
        .harga-float-bar {
            position: fixed;
            right: 1.5rem;
            bottom: 1.5rem;
            z-index: 1050;
            display: flex;
            gap: .75rem;
        }
        .harga-float-bar.d-none { display: none !important; }
        .harga-float-bar .btn {
            border-radius: .75rem;
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .18);
        }
        .harga-float-bar #bulkCancelHargaBtn {
            background-color: #fff;
        }
        .harga-float-bar #bulkCancelHargaBtn:hover {
            background-color: #6c757d;
        }
    </style>
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
            const tableEl = document.getElementById('goldReadyTable');
            if (!tableEl) return;
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;

            const dt = $('#goldReadyTable').DataTable({
                responsive: false,
                scrollX: true,
                paging: false,
                pageLength: -1,
                lengthChange: false,
                ordering: true,
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: -1, orderable: false }
                ],
                dom:
                    '<"card-header dt-head d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3"' +
                    '<"head-label">' +
                    '<"d-flex flex-column flex-sm-row align-items-center justify-content-sm-end gap-3 w-100"f<"add_button">>>' +
                    't' +
                    '<"card-footer d-flex flex-column align-items-center gap-2"' +
                    '<"row w-100 align-items-center g-2"' +
                        '<"col-12 d-flex align-items-center justify-content-center gap-2"i>' +
                    '>>',
                language: {
                    search: '',
                    searchPlaceholder: 'Search Files'
                }
            });

            const savedPage = sessionStorage.getItem('goldReadyTablePage');
            if (savedPage !== null) {
                const p = parseInt(savedPage, 10);
                if (!Number.isNaN(p)) {
                    dt.page(p).draw('page');
                }
                sessionStorage.removeItem('goldReadyTablePage');
            }

            const headLabel = document.querySelector('div.head-label');
            if (headLabel) {
                headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Daftar Stok Emas Ready</h5>';
            }

            const addBtnContainer = document.querySelector('.add_button');
            if (addBtnContainer) {
                addBtnContainer.innerHTML = '<div class="d-flex flex-wrap gap-2">'+
                    '<a class="btn btn-primary" href="{{ route('admin.master.ready-stocks.create') }}"><i class="bi bi-plus-lg fs-6 me-1"></i> Tambah Data</a>'+
                    '<form id="bulkDeactivateForm" action="{{ route('admin.master.ready-stocks.deactivate-all') }}" method="POST" class="d-inline">'+
                        '<input type="hidden" name="_token" value="{{ csrf_token() }}">'+
                    '</form>'+
                    '<form id="bulkActivateForm" action="{{ route('admin.master.ready-stocks.activate-all') }}" method="POST" class="d-inline">'+
                        '<input type="hidden" name="_token" value="{{ csrf_token() }}">'+
                    '</form>'+
                    '<button type="button" id="bulkEditHargaBtn" class="btn btn-outline-primary"><i class="ri-pencil-line me-1"></i> Edit Harga Jual Fix</button>'+
                    '<button type="button" id="bulkDeactivateBtn" class="btn btn-outline-danger"><i class="ri-close-circle-line me-1"></i> Nonaktifkan Semua</button>'+
                    '<button type="button" id="bulkActivateBtn" class="btn btn-outline-success"><i class="ri-check-line me-1"></i> Aktifkan Semua</button>'+
                '</div>';

                setupBulkHargaEdit(dt);
                document.getElementById('bulkDeactivateBtn')?.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Nonaktifkan Semua?',
                        html: 'Semua stok akan diubah menjadi <b>nonaktif</b>.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Nonaktifkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('bulkDeactivateForm')?.submit();
                        }
                    });
                });
                document.getElementById('bulkActivateBtn')?.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Aktifkan Semua?',
                        html: 'Semua stok akan diubah menjadi <b>aktif</b>.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Aktifkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('bulkActivateForm')?.submit();
                        }
                    });
                });
            }

            function setupBulkHargaEdit(dt) {
                const editBtn = document.getElementById('bulkEditHargaBtn');
                const bulkUrl = "{{ route('admin.master.ready-stocks.bulk-update-harga-jual-fix') }}";
                const csrf = "{{ csrf_token() }}";
                if (!editBtn) return;

                // Floating action bar (bottom-right), only visible while editing
                let floatBar = document.getElementById('hargaFloatBar');
                if (!floatBar) {
                    floatBar = document.createElement('div');
                    floatBar.id = 'hargaFloatBar';
                    floatBar.className = 'harga-float-bar d-none';
                    floatBar.innerHTML =
                        '<button type="button" id="bulkCancelHargaBtn" class="btn btn-outline-secondary btn-lg"><i class="ri-close-line me-1"></i> Batal</button>' +
                        '<button type="button" id="bulkSaveHargaBtn" class="btn btn-success btn-lg"><i class="ri-save-line me-1"></i> Simpan Harga</button>';
                    document.body.appendChild(floatBar);
                }
                const saveBtn = document.getElementById('bulkSaveHargaBtn');
                const cancelBtn = document.getElementById('bulkCancelHargaBtn');
                if (!saveBtn || !cancelBtn) return;

                function formatNumber(val) {
                    if (val === '' || val === null || isNaN(val)) return '-';
                    return 'Rp ' + Number(val).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                }

                function formatWithSeparator(digits) {
                    if (digits === '') return '';
                    return Number(digits).toLocaleString('id-ID', { maximumFractionDigits: 0 });
                }

                function enterEditMode() {
                    document.querySelectorAll('#goldReadyTable td.harga-jual-fix-cell').forEach(function (cell) {
                        const raw = cell.getAttribute('data-value') || '';
                        const digits = raw !== '' ? String(Math.round(parseFloat(raw))) : '';
                        cell.innerHTML =
                            '<div class="input-group input-group-lg" style="min-width: 200px;">' +
                                '<span class="input-group-text fw-semibold">Rp</span>' +
                                '<input type="text" inputmode="numeric" class="form-control harga-jual-fix-input fs-5" value="' + formatWithSeparator(digits) + '">' +
                            '</div>';
                    });

                    document.querySelectorAll('#goldReadyTable .harga-jual-fix-input').forEach(function (input) {
                        input.addEventListener('input', function () {
                            const digits = this.value.replace(/\D/g, '');
                            this.value = formatWithSeparator(digits);
                        });
                    });

                    editBtn.classList.add('d-none');
                    floatBar.classList.remove('d-none');
                }

                function exitEditMode() {
                    document.querySelectorAll('#goldReadyTable td.harga-jual-fix-cell').forEach(function (cell) {
                        const value = cell.getAttribute('data-value') || '';
                        cell.innerHTML = '<span class="harga-jual-fix-display fs-5 fw-semibold">' + formatNumber(value) + '</span>';
                    });
                    floatBar.classList.add('d-none');
                    editBtn.classList.remove('d-none');
                }

                editBtn.addEventListener('click', enterEditMode);
                cancelBtn.addEventListener('click', exitEditMode);

                saveBtn.addEventListener('click', function () {
                    const items = [];
                    document.querySelectorAll('#goldReadyTable td.harga-jual-fix-cell').forEach(function (cell) {
                        const id = cell.getAttribute('data-id');
                        const input = cell.querySelector('.harga-jual-fix-input');
                        if (!id || !input) return;
                        const digits = input.value.replace(/\D/g, '');
                        items.push({ id: parseInt(id, 10), harga_jual_fix: digits === '' ? null : digits });
                    });

                    if (items.length === 0) { exitEditMode(); return; }

                    saveBtn.disabled = true;
                    cancelBtn.disabled = true;

                    fetch(bulkUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ items: items })
                    })
                    .then(function (res) { return res.json().then(function (b) { return { ok: res.ok, body: b }; }); })
                    .then(function (r) {
                        saveBtn.disabled = false;
                        cancelBtn.disabled = false;
                        if (!r.ok || !r.body || !r.body.success) {
                            const msg = (r.body && r.body.message) ? r.body.message : 'Gagal memperbarui harga jual fix.';
                            Swal.fire({ title: 'Gagal', text: msg, icon: 'error' });
                            return;
                        }
                        // sync data-value with new inputs before exiting
                        document.querySelectorAll('#goldReadyTable td.harga-jual-fix-cell').forEach(function (cell) {
                            const input = cell.querySelector('.harga-jual-fix-input');
                            if (input) cell.setAttribute('data-value', input.value.replace(/\D/g, ''));
                        });
                        exitEditMode();
                        Swal.fire({
                            title: 'Berhasil',
                            text: r.body.message || 'Harga jual fix berhasil diperbarui.',
                            icon: 'success',
                            timer: 1800,
                            showConfirmButton: false
                        });
                    })
                    .catch(function () {
                        saveBtn.disabled = false;
                        cancelBtn.disabled = false;
                        Swal.fire({ title: 'Gagal', text: 'Terjadi kesalahan jaringan.', icon: 'error' });
                    });
                });
            }

            document.querySelectorAll('.delete-item').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const label = this.getAttribute('data-label') || 'Stok';
                    const row = this.closest('tr');
                    const form = row ? row.querySelector('form.delete-form') : null;
                    if (!form) return;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Anda yakin hapus Stok <b>${label}</b> ini?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            try {
                                const info = dt.page.info();
                                sessionStorage.setItem('goldReadyTablePage', String(info.page));
                            } catch (_) {}
                            form.submit();
                        }
                    });
                });
            });

            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);
        });
    </script>
@endsection
