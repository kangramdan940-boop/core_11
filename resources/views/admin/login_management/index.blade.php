@extends('layouts.admin.master')

@section('title', 'Login Management - Admin')
@section('sub-title', 'Login')
@section('breadcrumbExtra', 'Login Management')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.login-management.index'))

@section('content')
    <div class="card shadow-sm">
        <table id="loginTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th width="10px;">User ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jumlah Sesi</th>
                    <th>Aktivitas Terakhir</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $it)
                    <tr>
                        <td>{{ $it['user_id'] }}</td>
                        <td>{{ $it['name'] }}</td>
                        <td>{{ $it['email'] }}</td>
                        <td>{{ $it['session_count'] }}</td>
                        <td>{{ $it['last_activity'] ? \Illuminate\Support\Carbon::createFromTimestamp($it['last_activity'])->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <button type="button" class="btn icon-btn-sm btn-light-danger kick-item" data-label="{{ $it['name'] ?: ('#' . $it['user_id']) }}">
                                    <i class="ri-logout-box-line"></i>
                                </button>
                            </div>
                            <form action="{{ route('admin.login-management.kick') }}" method="POST" class="d-none kick-form">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $it['user_id'] }}">
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
            const tableEl = document.getElementById('loginTable');
            if (!tableEl) return;
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;

            const dt = $('#loginTable').DataTable({
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
                    sLengthMenu: '_MENU_ ',
                    search: '',
                    searchPlaceholder: 'Search Files',
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });

            const savedPage = sessionStorage.getItem('loginTablePage');
            if (savedPage !== null) {
                const p = parseInt(savedPage, 10);
                if (!Number.isNaN(p)) {
                    dt.page(p).draw('page');
                }
                sessionStorage.removeItem('loginTablePage');
            }

            const headLabel = document.querySelector('div.head-label');
            if (headLabel) {
                headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Daftar Login</h5>';
            }

            const addBtnContainer = document.querySelector('.add_button');
            if (addBtnContainer) {
                addBtnContainer.innerHTML = '';
            }

            document.querySelectorAll('.kick-item').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const label = this.getAttribute('data-label') || 'Pengguna';
                    const row = this.closest('tr');
                    const form = row ? row.querySelector('form.kick-form') : null;
                    if (!form) return;
                    Swal.fire({
                        title: 'Konfirmasi Logout Paksa',
                        html: `Anda yakin logout paksa <b>${label}</b> dari semua device?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            try {
                                const info = dt.page.info();
                                sessionStorage.setItem('loginTablePage', String(info.page));
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
