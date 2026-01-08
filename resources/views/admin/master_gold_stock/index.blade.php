@extends('layouts.admin.master')

@section('title', 'Master Stok Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Stok Emas (Antam)')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.gold-stocks.index'))
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.3.0/css/dataTables.checkboxes.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="card shadow-sm">
        <table id="goldStocksTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th width="10px;">ID</th>
                    <th>Mitra</th>
                    <th>No Faktur</th>
                    <th class="text-end">Gramasi (g)</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Berat (g)</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Total Pembayaran</th>
                    <th>Status</th>
                    <th>File Faktur</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stocks as $s)
                    <tr>
                        <td class="text-center">{{ $s->id }}</td>
                        <td>{{ optional($s->mitra)->nama_lengkap ?? '-' }}</td>
                        <td>{{ $s->no_faktur ?? '-' }}</td>
                        <td class="text-end">{{ number_format((float) ($s->gramasi ?? 0), 3) }}</td>
                        <td class="text-end">{{ (int) ($s->qty ?? 0) }}</td>
                        <td class="text-end">{{ number_format((float) ($s->berat ?? 0), 3) }}</td>
                        <td class="text-end">Rp {{ number_format((float) ($s->harga ?? 0), 2, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format((float) ($s->total_pembayaran ?? 0), 2, ',', '.') }}</td>
                        <td>
                            @php($norm = Str::lower(trim($s->status_pengambilan ?? '')))
                            @php($isDone = in_array($norm, ['sudah_diambil','sudah','diambil','selesai'], true))
                            @php($isPending = in_array($norm, ['belum_diambil','belum','pending','menunggu'], true))
                            @php($isCanceled = in_array($norm, ['batal','dibatalkan','cancel','void'], true))
                            @php($label = $isDone ? 'Sudah Diambil' : ($isPending ? 'Belum Diambil' : ($isCanceled ? 'Dibatalkan' : (empty($norm) ? '-' : Str::title($s->status_pengambilan)))))
                            @php($badgeClass = $isDone ? 'bg-success' : ($isPending ? 'bg-warning' : ($isCanceled ? 'bg-danger' : (empty($norm) ? 'bg-secondary' : 'bg-info'))))
                            <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                        </td>
                        <td>
                            @php($raw = $s->file_faktur_url ?? '')
                            @php($href = Str::startsWith($raw, ['http://','https://','/']) ? $raw : ($raw ? asset($raw) : ''))
                            @if(!empty($href))
                                <a href="{{ $href }}" target="_blank" rel="noopener">Buka</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.master.gold-stocks.edit', $s) }}" class="btn icon-btn-sm btn-light-primary">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="#" class="btn icon-btn-sm btn-light-danger delete-item"
                                   data-action="{{ route('admin.master.gold-stocks.destroy', $s) }}"
                                   data-label="{{ $s->no_faktur ? $s->no_faktur : ('#' . $s->id) }}">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.master.gold-stocks.destroy', $s) }}" method="POST" class="d-none delete-form">
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

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.3.0/js/dataTables.checkboxes.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.6.0/js/dataTables.select.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableEl = document.getElementById('goldStocksTable');
            if (!tableEl) return;
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;

            const dt = $('#goldStocksTable').DataTable({
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
                        '<"col-12 col-md-5 d-flex align-items-center justify-content-md-start justify-content-center gap-2"l i>' +
                        '<"col-12 col-md-7 d-flex justify-content-md-end justify-content-center"p>' +
                    '>>',
                language: {
                    emptyTable: 'Belum ada data stok emas.',
                    sLengthMenu: '_MENU_ ',
                    search: '',
                    searchPlaceholder: 'Cari',
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });

            const headLabel = document.querySelector('div.head-label');
            if (headLabel) {
                headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Daftar Stok Emas</h5>';
            }

            const addBtnContainer = document.querySelector('.add_button');
            if (addBtnContainer) {
                addBtnContainer.innerHTML = '<a class="btn btn-primary" href="{{ route('admin.master.gold-stocks.create') }}"><i class="bi bi-plus-lg fs-6 me-1"></i> Tambah Data</a>';
            }

            setTimeout(function () {
                const filterInput = document.querySelector('.dataTables_filter .form-control');
                const lengthSelect = document.querySelector('.dataTables_length .form-select');
                if (filterInput) filterInput.classList.remove('form-control-sm');
                if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
            }, 300);

            document.querySelectorAll('.delete-item').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const label = this.getAttribute('data-label') || 'Stok Emas';
                    const row = this.closest('tr');
                    const form = row ? row.querySelector('form.delete-form') : null;
                    if (!form) return;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Anda yakin hapus <b>${label}</b> ini?`,
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
        });
    </script>
@endsection
