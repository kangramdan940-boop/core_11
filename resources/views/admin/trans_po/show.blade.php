@extends('layouts.admin.master')

@section('title', 'Detail PO Emas - Admin')
@section('sub-title', 'Transaksi PO')
@section('breadcrumbExtra', 'Detail PO Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.po.index'))

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trans.po.index') }}" class="btn btn-secondary">← Kembali</a>
            <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-outline-secondary">Payment Logs</a>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.trans.po.update-status', $po) }}" method="POST" class="d-flex align-items-center gap-2">
                @csrf
                <div class="input-group" style="min-width:280px;">
                    <span class="input-group-text"><i class="ri-flag-2-line"></i></span>
                    <select name="status" class="form-select">
                        <option value="pending_payment" @selected($po->status==='pending_payment')>PENDING_PAYMENT</option>
                        <option value="paid" @selected($po->status==='paid')>PAID</option>
                        <option value="processing" @selected($po->status==='processing')>PROCESSING</option>
                        <option value="ready_at_agen" @selected($po->status==='ready_at_agen')>READY_AT_AGEN</option>
                        <option value="shipped" @selected($po->status==='shipped')>SHIPPED</option>
                        <option value="completed" @selected($po->status==='completed')>COMPLETED</option>
                        <option value="cancelled" @selected($po->status==='cancelled')>CANCELLED</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update Status</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Ringkasan PO</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Kode PO</strong>
                    <div class="mt-1">{{ $po->kode_po }}</div>
                </div>
                @php
                    $s = $po->status;
                    $badge = 'text-bg-secondary';
                    if ($s === 'paid' || $s === 'completed') { $badge = 'text-bg-success'; }
                    elseif ($s === 'cancelled') { $badge = 'text-bg-danger'; }
                    elseif ($s === 'pending_payment') { $badge = 'text-bg-warning'; }
                    elseif ($s === 'processing') { $badge = 'text-bg-info'; }
                    elseif ($s === 'ready_at_agen' || $s === 'shipped') { $badge = 'text-bg-primary'; }
                @endphp
                <div class="col-md-3">
                    <strong>Status</strong>
                    <div class="mt-1"><span class="badge rounded-pill {{ $badge }}">{{ strtoupper($s) }}</span></div>
                </div>
                <div class="col-md-3">
                    <strong>Total Gram</strong>
                    <div class="mt-1">{{ number_format((float)$po->total_gram, 3, ',', '.') }} g</div>
                </div>
                <div class="col-md-3">
                    <strong>Qty</strong>
                    <div class="mt-1">{{ (int)($po->qty ?? 1) }} pcs</div>
                </div>
                <div class="col-md-3">
                    <strong>Total Amount</strong>
                    <div class="mt-1">{{ number_format((float)$po->total_amount, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Customer</strong>
                    <div class="mt-1">{{ optional($po->customer)->full_name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Agen</strong>
                    <div class="mt-1">{{ optional($po->agen)->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Delivery</strong>
                    <div class="mt-1">{{ $po->delivery_type }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Metode Bayar</strong>
                    <div class="mt-1">{{ $po->payment_method ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Ordered At</strong>
                    <div class="mt-1">{{ optional($po->ordered_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Paid At</strong>
                    <div class="mt-1">{{ optional($po->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Processed At</strong>
                    <div class="mt-1">{{ optional($po->processed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Ready @Agen</strong>
                    <div class="mt-1">{{ optional($po->ready_at_agen_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Shipped At</strong>
                    <div class="mt-1">{{ optional($po->shipped_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Completed At</strong>
                    <div class="mt-1">{{ optional($po->completed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Cancelled At</strong>
                    <div class="mt-1">{{ optional($po->cancelled_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-12"><strong>Catatan</strong><br>{{ $po->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Data Pengiriman</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Nama</strong>
                    <div class="mt-1">{{ $po->shipping_name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>WhatsApp</strong>
                    <div class="mt-1">{{ $po->shipping_phone ?? '-' }}</div>
                </div>
                <div class="col-md-12">
                    <strong>Alamat</strong>
                    <div class="mt-1">{{ $po->shipping_address ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Kota</strong>
                    <div class="mt-1">{{ $po->shipping_city ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Provinsi</strong>
                    <div class="mt-1">{{ $po->shipping_province ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Kode Pos</strong>
                    <div class="mt-1">{{ $po->shipping_postal_code ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Pembagian Komisi Mitra (Assign)</h6>
            @php
                $mitras = \App\Models\MasterMitraBrankas::where('is_active', true)->orderBy('nama_lengkap')->get();
                $allocated = (float) (\App\Models\TransPoMitraKomisi::where('trans_po_id', $po->id)->sum('jumlah_gram') ?? 0);
                $remainingPo = max(0, (float)$po->total_gram - $allocated);
            @endphp
            <div class="row g-3 mb-3">
                <div class="col-md-3"><strong>Total Gram PO</strong><br>{{ number_format((float)$po->total_gram, 3, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Sudah Dialokasikan</strong><br>{{ number_format($allocated, 3, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Sisa Gram PO</strong><br>{{ number_format($remainingPo, 3, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Status</strong><br>{{ strtoupper($po->status) }}</div>
            </div>

            @if (in_array($po->status, ['paid','processing','ready_at_agen','shipped','completed']))
            @if ($remainingPo > 0)
            <form action="{{ route('admin.trans.po.mitra-komisi.store', $po) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Pilih Mitra</label>
                    <select name="master_mitra_brankas_id" class="form-select" required>
                        <option value="">-- Pilih Mitra --</option>
                        @foreach ($mitras as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_lengkap }} ({{ $m->kode_mitra }}) — Limit Harian: {{ number_format((float)$m->harian_limit_gram, 3, ',', '.') }} g</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Komisi</label>
                    <input type="date" name="tanggal_komisi" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah Gram</label>
                    <input type="number" name="jumlah_gram" step="0.001" min="0.001" class="form-control" placeholder="0.001" value="{{ number_format($remainingPo, 3, '.', '') }}" required>
                    <div class="form-text">Maks sesuai sisa gram PO dan limit harian mitra.</div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" rows="2" class="form-control" placeholder="Opsional"></textarea>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Assign Komisi</button>
                </div>
            </form>
            @endif
            @else
                <div class="alert alert-info mb-0">Assign komisi hanya tersedia jika status PO minimal <strong>PAID</strong>.</div>
            @endif
        </div>
    </div>
     
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Payment Logs Terkait</h6>
            <div class="table-responsive">
                <table id="paymentLogsTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
                    <thead class="bg-light bg-opacity-30">
                        <tr>
                            <th width="10px;">ID</th>
                            <th>Kode Payment</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Metode</th>
                            <th>Provider</th>
                            <th>Paid At</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paymentLogs as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->kode_payment }}</td>
                                <td>{{ strtoupper($l->status) }}</td>
                                <td>{{ number_format((float)$l->amount, 2, ',', '.') }} {{ $l->currency }}</td>
                                <td>{{ $l->payment_method ?? '-' }}</td>
                                <td>{{ $l->provider ?? '-' }}</td>
                                <td>{{ optional($l->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <a href="{{ route('admin.trans.payment-logs.show', $l) }}" class="btn icon-btn-sm btn-light-primary"><i class="bi bi-eye"></i></a>
                                        @if ($l->payment_method === 'manual_transfer' && $l->status === 'pending')
                                        <form action="{{ route('admin.trans.payment-logs.approve', $l) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="approve-btn btn icon-btn-sm btn-success"><i class="bi bi-check2"></i></button>
                                        </form>
                                        <form action="{{ route('admin.trans.payment-logs.reject', $l) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="reject-btn btn icon-btn-sm btn-outline-danger"><i class="bi bi-x"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">Belum ada payment log terkait.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
 
            <h6 class="mt-4 mb-2 fs-5"># Konfirmasi Pembayaran Manual dari Customer</h6>
            <div class="row g-3">
                @php $manualLogs = $paymentLogs->filter(fn($l) => ($l->payment_method === 'manual_transfer')); @endphp
                @forelse ($manualLogs as $l)
                    @php $payload = $l->request_payload ? json_decode($l->request_payload, true) : null; @endphp
                    <div class="col-12">
                        <div class="border rounded p-2 d-flex align-items-start gap-3">
                            <div style="width:140px;">
                                @if ($payload && !empty($payload['proof_path']))
                                    <img src="{{ asset($payload['proof_path']) }}" alt="Bukti Transfer" class="img-fluid rounded border">
                                @else
                                    <div class="text-muted small">Tidak ada bukti</div>
                                @endif
                            </div>
                            <div class="flex-grow-1 small">
                                <div><strong>Kode Payment:</strong> {{ $l->kode_payment }}</div>
                                <div><strong>Nama Pengirim:</strong> {{ $payload['sender_name'] ?? '-' }}</div>
                                <div><strong>Nominal:</strong> {{ number_format((float)$l->amount, 2, ',', '.') }} {{ $l->currency }}</div>
                                <div><strong>Status:</strong> {{ strtoupper($l->status) }}</div>
                                <div><strong>Dikirim:</strong> {{ optional($l->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted small">Belum ada konfirmasi pembayaran manual.</div>
                @endforelse
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableEl = document.getElementById('paymentLogsTable');
            if (tableEl && typeof $ !== 'undefined' && $.fn.DataTable) {
                const dt = $('#paymentLogsTable').DataTable({
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
                            '<"col-12 col-md-8 d-flex align-items-center justify-content-md-start justify-content-center gap-2"l i>' +
                            '<"col-12 col-md-4 d-flex justify-content-md-end justify-content-center"p>' +
                        '>>',
                    language: {
                        sLengthMenu: '_MENU_ ',
                        search: '',
                        searchPlaceholder: 'Search Payments',
                        paginate: {
                            next: '<i class="ri-arrow-right-s-line"></i>',
                            previous: '<i class="ri-arrow-left-s-line"></i>'
                        }
                    }
                });
                setTimeout(function () {
                    const filterInput = document.querySelector('.dataTables_filter .form-control');
                    const lengthSelect = document.querySelector('.dataTables_length .form-select');
                    if (filterInput) filterInput.classList.remove('form-control-sm');
                    if (lengthSelect) lengthSelect.classList.remove('form-select-sm');
                }, 300);
            }
            document.querySelectorAll('.approve-btn').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Konfirmasi Approve',
                        text: 'Apakah Anda yakin menyetujui pembayaran ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Approve',
                        cancelButtonText: 'Batal'
                    }).then(function(result){ if (result.isConfirmed && form) form.submit(); });
                });
            });
            document.querySelectorAll('.reject-btn').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Konfirmasi Reject',
                        text: 'Apakah Anda yakin menolak pembayaran ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Reject',
                        cancelButtonText: 'Batal'
                    }).then(function(result){ if (result.isConfirmed && form) form.submit(); });
                });
            });
        });
    </script>
@endsection