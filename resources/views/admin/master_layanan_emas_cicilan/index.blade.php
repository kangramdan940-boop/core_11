@extends('layouts.admin.master')

@section('title', 'Master Layanan Emas Cicilan - Admin')
@section('sub-title', 'Layanan Emas Cicilan')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.layanan-emas-cicilan.index'))

@section('content')
    <div class="card shadow-sm">
        <table id="layananTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th width="10px;">ID</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Tenor</th>
                    <th>DP (%)</th>
                    <th>Status</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $it)
                    <tr>
                        <td class="text-center">{{ $it->id }}</td>
                        <td>{{ $it->kode_layanan }}</td>
                        <td>{{ $it->nama_layanan }}</td>
                        <td class="text-center">{{ (int)$it->tenor_min_bulan }}–{{ (int)$it->tenor_max_bulan }}</td>
                        <td class="text-end">{{ number_format((float)$it->dp_min_persen, 2) }}–{{ number_format((float)$it->dp_max_persen, 2) }}</td>
                        <td class="text-center">
                            @if($it->is_active)
                                <span class="badge rounded-pill bg-success">Aktif</span>
                            @else
                                <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.master.layanan-emas-cicilan.edit', $it) }}" class="btn icon-btn-sm btn-light-primary">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="#" class="btn icon-btn-sm btn-light-danger delete-item"
                                   data-action="{{ route('admin.master.layanan-emas-cicilan.destroy', $it) }}"
                                   data-label="{{ $it->nama_layanan ?: ('#' . $it->id) }}">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.master.layanan-emas-cicilan.destroy', $it) }}" method="POST" class="d-none delete-form">
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
            const tableEl = document.getElementById('layananTable');
            if (!tableEl) return;
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;

            const dt = $('#layananTable').DataTable({
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
                    '<"d-flex flex-column flex-sm-row align-items-center justify-content-sm-end gap-3 w-100"f<"add_button">>>' +
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
                headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Daftar Layanan Emas Cicilan</h5>';
            }

            const addBtnContainer = document.querySelector('.add_button');
            if (addBtnContainer) {
                addBtnContainer.innerHTML = '<a class="btn btn-primary" href="{{ route('admin.master.layanan-emas-cicilan.create') }}"><i class="bi bi-plus-lg fs-6 me-1"></i> Tambah Data</a>';
            }

            document.querySelectorAll('.delete-item').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const form = row ? row.querySelector('form.delete-form') : null;
                    const label = this.getAttribute('data-label') || 'Layanan';
                    if (!form) return;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Anda yakin hapus Layanan <b>${label}</b> ini?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
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