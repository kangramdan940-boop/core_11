@extends('layouts.admin.master')

@section('title', 'Detail Keranjang - Admin')
@section('sub-title', 'Transaksi Keranjang')
@section('breadcrumbExtra', 'Detail Keranjang')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.keranjang.index'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.trans.keranjang.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    @if(($keranjang->status_order ?? '') === 'perlu_dibayar')
        <form action="{{ route('admin.trans.keranjang.approve-payment', $keranjang) }}" method="POST" onsubmit="return confirm('Setujui pembayaran keranjang ini dan ubah semua PO menjadi PAID?')">
            @csrf
            <button type="submit" class="btn btn-success">Approve Pembayaran</button>
        </form>
    @endif
</div>
 

<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-2">Update Status Keranjang</h6>
        @php($options = ['pending_payment' => 'Pending Payment','paid' => 'Paid','processing' => 'Diproses','ready_at_agen' => 'Siap di Agen','shipped' => 'Dikirim','completed' => 'Selesai','cancelled' => 'Dibatalkan'])
        @php($syn = ['perlu_dibayar'=>'pending_payment','terbayar'=>'paid','diproses'=>'processing','dikirim'=>'shipped','selesai'=>'completed','dibatalkan'=>'cancelled'])
        @php($cur = strtolower((string)($keranjang->status_order ?? '')))
        @php($curNorm = $syn[$cur] ?? $cur)
        <form action="{{ route('admin.trans.keranjang.update', $keranjang) }}" method="POST" class="row g-2 align-items-end mb-3" data-index-url="{{ route('admin.trans.keranjang.index') }}">
            @csrf
            @method('PUT')
            <div class="col-12 col-md-6">
                <label class="form-label mb-1">Status Keranjang</label>
                <select name="status_order" class="form-select">
                    @foreach($options as $val => $label)
                        <option value="{{ $val }}" @selected($curNorm === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <input type="hidden" name="force" value="1">
                <button type="button" class="btn btn-primary js-confirm-update">Update Status</button>
            </div>
        </form>
        <h6 class="mb-3">Info Keranjang</h6>
        <div class="row g-3">
            <div class="col-12 col-md-4"><strong>Kode:</strong> {{ $keranjang->kode_keranjang }}</div>
            <div class="col-12 col-md-4"><strong>Status Order:</strong> {{ $keranjang->status_order }}</div>
            <div class="col-12 col-md-4"><strong>Status Kadaluarsa:</strong> {{ $keranjang->status_kadaluarsa }}</div>
            <div class="col-12 col-md-4"><strong>Kadaluarsa:</strong> {{ optional($keranjang->expires_at)->format('Y-m-d H:i') ?? '-' }}</div>
            <div class="col-12 col-md-4"><strong>Ongkir:</strong> {{ number_format((float)($keranjang->ongkos_kirim ?? 0), 2, ',', '.') }}</div>
            <div class="col-12 col-md-4"><strong>Catatan:</strong> {{ $keranjang->catatan ?? '-' }}</div>
            <div class="col-12 col-md-4"><strong>Nama Pengirim:</strong> {{ $keranjang->nama_pengirim ?? '-' }}</div>
            <div class="col-12 col-md-4"><strong>Nominal Transfer:</strong> {{ number_format((float)($keranjang->nominal_transfer ?? 0), 2, ',', '.') }}</div>
            <div class="col-12 col-md-4"><strong>Bukti Transfer:</strong>
                @if(!empty($keranjang->bukti_transfer_url))
                    <a href="{{ asset($keranjang->bukti_transfer_url) }}" target="_blank" class="text-decoration-underline">Lihat</a>
                @else
                    -
                @endif
            </div>
            <div class="col-12 col-md-4"><strong>Resi Ekspedisi:</strong> {{ $keranjang->resi_ekspedisi ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @php($isReadyKeranjang = strpos((string)($keranjang->kode_keranjang ?? ''), 'KRG-READY-') !== false)
        @if($isReadyKeranjang)
            <h6 class="mb-3">Ready dalam Keranjang</h6>
            <table class="table table-sm table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Kode Ready</th>
                        <th>Customer</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Biaya Pengiriman</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($readies as $r)
                        <tr>
                            <td>{{ $r->kode_trans }}</td>
                            <td>{{ optional($r->customer)->full_name ?? '-' }}</td>
                            <td>{{ optional($r->readyStock)->kode_item ?? '-' }}</td>
                            <td>{{ (int)($r->qty ?? 0) }}</td>
                            <td>{{ number_format((float)($r->shipping_cost ?? 0), 2, ',', '.') }}</td>
                            <td>{{ number_format((float)($r->total_amount ?? 0), 2, ',', '.') }}</td>
                            <td>{{ strtoupper((string)($r->status ?? '-')) }}</td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        @else
            <h6 class="mb-3">PO dalam Keranjang</h6>
            <table class="table table-sm table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Kode PO</th>
                        <th>Customer</th>
                        <th>Gramasi (Qty)</th>
                        <th>Total Gram</th>
                        <th>Biaya Pengiriman</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pos as $p)
                        <tr>
                            <td>{{ $p->kode_po }}</td>
                            <td>{{ optional($p->customer)->full_name ?? '-' }}</td>
                            <td>{{ number_format((float)(optional(optional($p->produk)->gramasi)->gramasi ?? 0), 3, ',', '.') }} gr x ({{ (int)($p->qty ?? 0) }})</td>
                            <td>{{ number_format((float)($p->total_gram ?? 0), 3, ',', '.') }}</td>
                            <td>{{ number_format((float)($p->shipping_cost ?? 0), 2, ',', '.') }}</td>
                            <td>{{ number_format((float)($p->total_amount ?? 0), 2, ',', '.') }}</td>
                            <td>{{ strtoupper((string)($p->status ?? '-')) }}</td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-confirm-update').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = this.closest('form');
                if (!form) return;
                var select = form.querySelector('select[name="status_order"]');
                var val = select ? select.value : '';
                var labelMap = {
                    pending_payment: 'Pending Payment',
                    paid: 'Paid',
                    processing: 'Diproses',
                    ready_at_agen: 'Siap di Agen',
                    shipped: 'Dikirim',
                    completed: 'Selesai',
                    cancelled: 'Dibatalkan'
                };
                var label = labelMap[val] || val;
                Swal.fire({
                    title: 'Konfirmasi Perubahan Status',
                    text: 'Ubah status keranjang menjadi ' + label + ' dan samakan semua PO terkait?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: { confirmButton: 'btn btn-primary', cancelButton: 'btn btn-outline-secondary' }
                }).then(function (res) {
                    if (res.isConfirmed) {
                        var fd = new FormData(form);
                        fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd })
                            .then(function (r) { if (!r.ok) throw new Error('Failed'); return r.json(); })
                            .then(function () {
                                var base = form.getAttribute('data-index-url') || '{{ route('admin.trans.keranjang.index') }}';
                                var target = base + '?status=' + encodeURIComponent(val);
                                Swal.fire('Berhasil', 'Status diperbarui.', 'success').then(function(){ window.location.href = target; });
                            })
                            .catch(function () { Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui status.', 'error'); });
                    }
                });
            });
        });
    });
</script>
@endsection