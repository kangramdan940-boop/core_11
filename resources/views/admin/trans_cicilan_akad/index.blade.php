@extends('layouts.admin.master')

@section('title', 'Akad Murabahah - Admin')
@section('sub-title', 'Akad Murabahah (Cicilan)')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan-akad.index'))

@section('content')
    <div class="card shadow-sm">
        <table id="akadTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th width="10px;">ID</th>
                    <th>Nomor Akad</th>
                    <th>Cicilan Emas</th>

                    <th>Tgl Akad</th>
                    <th>Status</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $it)
                    <tr>
                        <td class="text-center">{{ $it->id }}</td>
                        <td><a href="{{ route('admin.trans.cicilan-akad.show', $it) }}" class="text-decoration-underline">{{ $it->nomor_akad }}</a></td>
                        <td>{{ $it->kontrak ? ('#'.$it->kontrak->id.' — '.number_format((float)$it->gramasi_total,3,',','.') . ' g') : '-' }}</td>

                        <td>{{ $it->tanggal_akad ? $it->tanggal_akad->toDateString() : '-' }}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-primary">{{ ucfirst(str_replace('_', ' ', $it->status)) }}</span>
                        </td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.trans.cicilan-akad.edit', $it) }}" class="btn icon-btn-sm btn-light-primary">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="#" class="btn icon-btn-sm btn-light btn-preview-pdf"
                                   data-pdf-url="{{ $it->file_pdf_url ? asset($it->file_pdf_url) : '' }}" @if(!$it->file_pdf_url) disabled @endif>
                                    <i class="ri-file-pdf-line"></i>
                                </a>
                                <a href="#" class="btn icon-btn-sm btn-light-danger delete-item"
                                   data-action="{{ route('admin.trans.cicilan-akad.destroy', $it) }}"
                                   data-label="{{ $it->nomor_akad }}">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.trans.cicilan-akad.destroy', $it) }}" method="POST" class="d-none delete-form">
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
    <div class="modal fade" id="pdfPreviewModal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <iframe id="pdfPreviewFrame" src="" style="border:0;width:100%;height:80vh;"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.6.0/js/dataTables.select.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableEl = document.getElementById('akadTable');
            if (!tableEl) return;
            if (typeof $ === 'undefined' || !$.fn.DataTable) return;

            const dt = $('#akadTable').DataTable({
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
            if (headLabel) headLabel.innerHTML = '<h5 class="card-title text-nowrap mb-0">Daftar Akad Murabahah</h5>';

            const addBtnContainer = document.querySelector('.add_button');
            const manualUrl = "{{ route('admin.trans.cicilan-akad.create') }}";
            const simpleUrl = "{{ route('admin.trans.cicilan-akad.create-simple') }}";
            if (addBtnContainer) {
                addBtnContainer.innerHTML =
                    '<a class="btn btn-primary" href="' + simpleUrl + '"><i class="bi bi-plus-lg fs-6 me-1"></i> Buat Akad</a>' +
                    ' <a class="btn btn-success" href="' + manualUrl + '"><i class="ri-hand-coin-line"></i> Transaksi Manual</a>';
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
                    const row = this.closest('tr');
                    const form = row ? row.querySelector('form.delete-form') : null;
                    const label = this.getAttribute('data-label') || 'Akad';
                    if (!form) return;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Anda yakin hapus Akad <b>${label}</b> ini?`,
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

            document.querySelectorAll('.btn-preview-pdf').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('data-pdf-url');
                    if (!url) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'info', title: 'PDF belum ada', text: 'Unggah PDF akad terlebih dahulu.' });
                        }
                        return;
                    }
                    const frame = document.getElementById('pdfPreviewFrame');
                    if (frame) { frame.src = url; }
                    const modalEl = document.getElementById('pdfPreviewModal');
                    if (!modalEl) return;
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: false });
                        modal.show();
                    } else {
                        modalEl.classList.add('show');
                        modalEl.style.display = 'block';
                    }
                });
            });
        });
    </script>
@endsection