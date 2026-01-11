@extends('layouts.admin.master')

@section('title', 'Keranjang - Admin')
@section('sub-title', 'Transaksi Keranjang')
@section('breadcrumbExtra', 'Keranjang')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.keranjang.index'))

@section('content')
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form action="{{ route('admin.trans.keranjang.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Tanggal Dibuat</label>
                <input type="date" name="created_date" class="form-control" value="{{ request('created_date') }}">
            </div>
            <div class="col-12 col-md-5">
                <label class="form-label mb-1">Status Order</label>
                <select name="status" class="form-select">
                    @php($allowed = ['' => 'Semua','pending_payment' => 'Pending Payment','paid' => 'Paid','processing' => 'Diproses','ready_at_agen' => 'Siap di Agen','shipped' => 'Dikirim','completed' => 'Selesai','cancelled' => 'Dibatalkan'])
                    @foreach($allowed as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.trans.keranjang.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div id="keranjang-list" data-index-url="{{ (request('status') || request('created_date')) ? route('admin.trans.keranjang.index', ['status' => request('status'), 'created_date' => request('created_date')]) : route('admin.trans.keranjang.index') }}">
<ul class="nav nav-tabs mb-3">
    @foreach($allowed as $val => $label)
        @php($active = (request('status') === $val) || ($val === '' && (request('status') === null || request('status') === '')))
        @php($count = $val === '' ? ($totalCount ?? 0) : (($statusCounts ?? [])[$val] ?? 0))
        <li class="nav-item">
            <a class="nav-link {{ $active ? 'active' : '' }} d-flex align-items-center justify-content-between" href="{{ $val === '' ? route('admin.trans.keranjang.index', request()->has('created_date') ? ['created_date' => request('created_date')] : []) : route('admin.trans.keranjang.index', ['status' => $val, 'created_date' => request('created_date')]) }}">
                <span>{{ $label }}</span>
                <span class="badge rounded-pill text-bg-secondary ms-2">{{ $count }}</span>
            </a>
        </li>
    @endforeach
</ul>
<div class="card shadow-sm">
    <table class="table table-hover align-middle table-nowrap w-100">
        <thead class="bg-light bg-opacity-30">
            <tr>
                <th>No</th>
                <th>Kode Keranjang</th>
                <th>Status Order</th>
                <th>Status Kadaluarsa</th>
                <th>Kadaluarsa</th>
                <th>Ongkos Kirim</th>
                <th>Catatan</th>
                <th>Resi Ekspedisi</th>
                <th>Jumlah PO</th>
                <th>Bukti Transfer</th>
                <th>Dibuat</th>
                <th style="width:90px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keranjangs as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->kode_keranjang }}</td>
                    @php($cur = strtolower((string)($k->status_order ?? '')))
                    @php($syn = ['perlu_dibayar'=>'pending_payment','terbayar'=>'paid','diproses'=>'processing','dikirim'=>'shipped','selesai'=>'completed','dibatalkan'=>'cancelled'])
                    @php($curNorm = $syn[$cur] ?? $cur)
                    @php($label = ucwords(str_replace('_',' ', $curNorm)))
                    <td><span class="badge bg-secondary">{{ $label }}</span></td>
                    <td><span class="badge {{ ($k->status_kadaluarsa === 'expired') ? 'bg-danger' : 'bg-success' }}">{{ strtoupper((string)($k->status_kadaluarsa ?? '-')) }}</span></td>
                    <td>{{ optional($k->expires_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    <td>{{ number_format((float)($k->ongkos_kirim ?? 0), 2, ',', '.') }}</td>
                    <td>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="me-2">{{ (string)($k->catatan ?? '-') }}</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#noteEditModal-{{ $k->id }}">Update</button>
                        </div>
                        <div class="modal fade" id="noteEditModal-{{ $k->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Catatan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.trans.keranjang.update', $k) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="catatan-{{ $k->id }}" class="form-label">Catatan</label>
                                                <textarea id="catatan-{{ $k->id }}" name="catatan" class="form-control" rows="3">{{ old('catatan', (string)($k->catatan ?? '')) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if(($curNorm ?? '') === 'shipped')
                            @php($posList = $k->pos()->get())
                            @php($firstResi = $posList->firstWhere('resi_number', '!=', null))
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="me-2">
                                    @if(!$firstResi)
                                        -
                                    @else
                                        {{ $firstResi->resi_number }} ({{ $firstResi->resi_courier ?? '-' }})
                                    @endif
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resiEditModal-{{ $k->id }}">Update</button>
                            </div>
                            <div class="modal fade" id="resiEditModal-{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Resi Ekspedisi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="nav nav-tabs mb-3">
                                                <li class="nav-item">
                                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabKeranjang-{{ $k->id }}" type="button">Keranjang</button>
                                                </li>
                                                <li class="nav-item">
                                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabPerPo-{{ $k->id }}" type="button">Per PO</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="tabKeranjang-{{ $k->id }}">
                                                    <form action="{{ route('admin.trans.keranjang.resi.update', $k) }}" method="POST" class="row g-2">
                                                        @csrf
                                                        <div class="col-12 col-md-4">
                                                            <input type="text" name="resi_number" class="form-control" placeholder="Nomor Resi" value="{{ old('resi_number', (string)($firstResi->resi_number ?? '')) }}">
                                                        </div>
                                                        <div class="col-12 col-md-4">
                                                            <input type="text" name="resi_courier" class="form-control" placeholder="Kurir" value="{{ old('resi_courier', (string)($firstResi->resi_courier ?? '')) }}">
                                                        </div>
                                                        <div class="col-12 col-md-4">
                                                            <input type="text" name="resi_service" class="form-control" placeholder="Layanan" value="{{ old('resi_service', (string)($firstResi->resi_service ?? '')) }}">
                                                        </div>
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="tab-pane fade" id="tabPerPo-{{ $k->id }}">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th>Kode PO</th>
                                                                    <th style="width: 60%">Resi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($posList as $p)
                                                                    <tr>
                                                                        <td>{{ $p->kode_po }}</td>
                                                                        <td>
                                                                            <form action="{{ route('admin.trans.po.resi.update', $p) }}" method="POST" class="d-flex gap-2">
                                                                                @csrf
                                                                                <input type="text" name="resi_number" class="form-control form-control-sm" placeholder="Nomor Resi" value="{{ old('resi_number', (string)($p->resi_number ?? '')) }}">
                                                                                <input type="text" name="resi_courier" class="form-control form-control-sm" placeholder="Kurir" value="{{ old('resi_courier', (string)($p->resi_courier ?? '')) }}">
                                                                                <input type="text" name="resi_service" class="form-control form-control-sm" placeholder="Layanan" value="{{ old('resi_service', (string)($p->resi_service ?? '')) }}">
                                                                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ (int)($k->pos_count ?? 0) }}</td>
                    <td>
                        @if(!empty($k->bukti_transfer_url))
                            <a href="#" class="proof-preview" data-bs-toggle="modal" data-bs-target="#proofPreviewModal-{{ $k->id }}">
                                <img src="{{ asset($k->bukti_transfer_url) }}" alt="Bukti Transfer" style="height:70px; width:auto;" />
                            </a>
                            <div class="modal fade" id="proofPreviewModal-{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Preview Bukti Transfer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset($k->bukti_transfer_url) }}" alt="Preview Bukti Transfer" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ optional($k->created_at)->format('Y-m-d H:i') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.trans.keranjang.show', $k) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            @php($cur = strtolower((string)($k->status_order ?? '')))
                            @php($syn = ['perlu_dibayar'=>'pending_payment','terbayar'=>'paid','diproses'=>'processing','dikirim'=>'shipped','selesai'=>'completed','dibatalkan'=>'cancelled'])
                            @php($curNorm = $syn[$cur] ?? $cur)
                            @php($flow = ['pending_payment'=>'paid','paid'=>'processing','processing'=>'ready_at_agen','ready_at_agen'=>'shipped','shipped'=>'completed'])
                            @php($next = $flow[$curNorm] ?? null)
                            @if($next)
                                @php($nextLabel = ucwords(str_replace('_',' ', $next)))
                                <form action="{{ route('admin.trans.keranjang.update', $k) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_order" value="{{ $next }}">
                                    <button type="button" class="btn btn-sm btn-primary js-confirm-status" data-label="{{ $nextLabel }}">{{ $nextLabel }}</button>
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
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function reloadKeranjangList() {
            var wrapper = document.getElementById('keranjang-list');
            var url = wrapper ? wrapper.getAttribute('data-index-url') : null;
            if (!wrapper || !url) { window.location.reload(); return; }
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var fresh = doc.getElementById('keranjang-list');
                    if (fresh && wrapper) { wrapper.innerHTML = fresh.innerHTML; }
                })
                .catch(function () { /* ignore */ });
        }

        document.querySelectorAll('.js-confirm-status').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = this.closest('form');
                var label = this.getAttribute('data-label') || 'Update';
                Swal.fire({
                    title: 'Konfirmasi Perubahan Status',
                    text: 'Apakah Anda yakin ingin mengubah status menjadi ' + label + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: { confirmButton: 'btn btn-primary', cancelButton: 'btn btn-outline-secondary' }
                }).then(function (result) {
                    if (result.isConfirmed && form) {
                        var fd = new FormData(form);
                        fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd })
                            .then(function (r) { if (!r.ok) throw new Error('Failed'); return r.json().catch(function(){ return {success:true}; }); })
                            .then(function () { reloadKeranjangList(); Swal.fire('Berhasil', 'Status berhasil diperbarui.', 'success'); })
                            .catch(function () { Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui status.', 'error'); });
                    }
                });
            });
        });
    });
</script>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-confirm-status').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = this.closest('form');
                var label = this.getAttribute('data-label') || 'Update';
                Swal.fire({
                    title: 'Konfirmasi Perubahan Status',
                    text: 'Apakah Anda yakin ingin mengubah status menjadi ' + label + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: { confirmButton: 'btn btn-primary', cancelButton: 'btn btn-outline-secondary' }
                }).then(function (result) {
                    if (result.isConfirmed && form) { form.submit(); }
                });
            });
        });
    });
</script>
@endsection