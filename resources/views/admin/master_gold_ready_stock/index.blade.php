@extends('layouts.admin.master')

@section('title', 'Master Stok Emas Ready - Admin')
@section('sub-title', 'Stok Emas Ready')
@section('breadcrumbExtra', 'Master Stok Emas Ready')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.ready-stocks.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="goldReadyTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
                    <thead class="bg-light bg-opacity-30">
                        <tr>
                            <th width="10px;">ID</th>
                            <th>Kode Item</th>
                            <th>Brand</th>
                            <th>Gramasi (g)</th>
                            <th>Agen</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>Harga Jual Fix</th>
                            <th style="width: 75px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ $s->kode_item }}</td>
                                <td>{{ $s->brand }}</td>
                                <td>{{ number_format((float)$s->gramasi, 3, ',', '.') }}</td>
                                <td>{{ optional($s->agen)->name ?? '-' }}</td>
                                <td>{{ ucfirst($s->kondisi_barang) }}</td>
                                <td>
                                    @if($s->status === 'available')
                                        <span class="badge bg-success">Available</span>
                                    @elseif($s->status === 'reserved')
                                        <span class="badge bg-warning text-dark">Reserved</span>
                                    @else
                                        <span class="badge bg-secondary">Sold</span>
                                    @endif
                                </td>
                                <td>{{ $s->harga_jual_fix !== null ? number_format((float)$s->harga_jual_fix, 2, ',', '.') : '-' }}</td>
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
                            <tr>
                                <td colspan="9" class="text-center py-3">Belum ada data stok emas ready.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stocks->hasPages())
                <div class="p-2">{{ $stocks->links() }}</div>
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
            const tableEl = document.getElementById('goldReadyTable');
            if (!tableEl) return;
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;

            const dt = $('#goldReadyTable').DataTable({
                responsive: false,
                scrollX: true,
                lengthMenu: [10, 20, 50],
                pageLength: 10,
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
                        '<"col-12 col-md-8 d-flex align-items-center justify-content-md-start justify-content-center gap-2"l i>' +
                        '<"col-12 col-md-4 d-flex justify-content-md-end justify-content-center"p>' +
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
                addBtnContainer.innerHTML = '<a class="btn btn-primary" href="{{ route('admin.master.ready-stocks.create') }}"><i class="bi bi-plus-lg fs-6 me-1"></i> Tambah Data</a>';
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
