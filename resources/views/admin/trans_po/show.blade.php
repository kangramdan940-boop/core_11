@extends('layouts.admin')

@section('title', 'Detail PO Emas - Admin')
@section('page_title', 'Detail PO Emas')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trans.po.index') }}" class="btn btn-sm btn-secondary">← Kembali</a>
            <a href="{{ route('admin.trans.payment-logs.index') }}" class="btn btn-sm btn-outline-secondary">Payment Logs</a>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.trans.po.update-status', $po) }}" method="POST" class="d-flex align-items-center gap-2">
                @csrf
                <select name="status" class="form-select form-select-sm" style="min-width:180px;">
                    <option value="pending_payment" @selected($po->status==='pending_payment')>PENDING_PAYMENT</option>
                    <option value="paid" @selected($po->status==='paid')>PAID</option>
                    <option value="processing" @selected($po->status==='processing')>PROCESSING</option>
                    <option value="ready_at_agen" @selected($po->status==='ready_at_agen')>READY_AT_AGEN</option>
                    <option value="shipped" @selected($po->status==='shipped')>SHIPPED</option>
                    <option value="completed" @selected($po->status==='completed')>COMPLETED</option>
                    <option value="cancelled" @selected($po->status==='cancelled')>CANCELLED</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan PO</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode PO</strong><br>{{ $po->kode_po }}</div>
                @php
                    $s = $po->status;
                    $badge = 'text-bg-secondary';
                    if ($s === 'paid' || $s === 'completed') { $badge = 'text-bg-success'; }
                    elseif ($s === 'cancelled') { $badge = 'text-bg-danger'; }
                    elseif ($s === 'pending_payment') { $badge = 'text-bg-warning'; }
                    elseif ($s === 'processing') { $badge = 'text-bg-info'; }
                    elseif ($s === 'ready_at_agen' || $s === 'shipped') { $badge = 'text-bg-primary'; }
                @endphp
                <div class="col-md-3"><strong>Status</strong><br><span class="badge rounded-pill {{ $badge }}">{{ strtoupper($s) }}</span></div>
                <div class="col-md-3"><strong>Total Gram</strong><br>{{ number_format((float)$po->total_gram, 3, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Qty</strong><br>{{ (int)($po->qty ?? 1) }} pcs</div>
                <div class="col-md-3"><strong>Total Amount</strong><br>{{ number_format((float)$po->total_amount, 2, ',', '.') }}</div>

                <div class="col-md-3"><strong>Customer</strong><br>{{ optional($po->customer)->full_name ?? '-' }}</div>
                <div class="col-md-3"><strong>Agen</strong><br>{{ optional($po->agen)->name ?? '-' }}</div>
                <div class="col-md-3"><strong>Delivery</strong><br>{{ $po->delivery_type }}</div>
                <div class="col-md-3"><strong>Metode Bayar</strong><br>{{ $po->payment_method ?? '-' }}</div>

                <div class="col-md-3"><strong>Ordered At</strong><br>{{ optional($po->ordered_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Paid At</strong><br>{{ optional($po->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Processed At</strong><br>{{ optional($po->processed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Ready @Agen</strong><br>{{ optional($po->ready_at_agen_at)->format('Y-m-d H:i') ?? '-' }}</div>

                <div class="col-md-3"><strong>Shipped At</strong><br>{{ optional($po->shipped_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Completed At</strong><br>{{ optional($po->completed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-3"><strong>Cancelled At</strong><br>{{ optional($po->cancelled_at)->format('Y-m-d H:i') ?? '-' }}</div>
                <div class="col-md-12"><strong>Catatan</strong><br>{{ $po->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Data Pengiriman</h6>
            <div class="row g-3">
                <div class="col-md-6"><strong>Nama</strong><br>{{ $po->shipping_name ?? '-' }}</div>
                <div class="col-md-6"><strong>WhatsApp</strong><br>{{ $po->shipping_phone ?? '-' }}</div>
                <div class="col-md-12"><strong>Alamat</strong><br>{{ $po->shipping_address ?? '-' }}</div>
                <div class="col-md-4"><strong>Kota</strong><br>{{ $po->shipping_city ?? '-' }}</div>
                <div class="col-md-4"><strong>Provinsi</strong><br>{{ $po->shipping_province ?? '-' }}</div>
                <div class="col-md-4"><strong>Kode Pos</strong><br>{{ $po->shipping_postal_code ?? '-' }}</div>
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
            <h6 class="mb-3">Payment Logs Terkait</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode Payment</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Metode</th>
                            <th>Provider</th>
                            <th>Paid At</th>
                            <th style="width:120px;">Aksi</th>
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
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <a href="{{ route('admin.trans.payment-logs.show', $l) }}" class="btn btn-sm btn-outline-primary py-0 px-2">Detail</a>
                                        @if ($l->payment_method === 'manual_transfer' && $l->status === 'pending')
                                        <form action="{{ route('admin.trans.payment-logs.approve', $l) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success py-0 px-2">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.trans.payment-logs.reject', $l) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger py-0 px-2">Reject</button>
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
 
            <h6 class="mt-4 mb-2">Konfirmasi Pembayaran Manual dari Customer</h6>
            <div class="row g-3">
                @php $manualLogs = $paymentLogs->filter(fn($l) => ($l->payment_method === 'manual_transfer')); @endphp
                @forelse ($manualLogs as $l)
                    @php $payload = $l->request_payload ? json_decode($l->request_payload, true) : null; @endphp
                    <div class="col-12">
                        <div class="border rounded p-2 d-flex align-items-start gap-3">
                            <div style="width:140px;">
                                @if ($payload && !empty($payload['proof_path']))
                                    <img src="{{ asset('storage/' . $payload['proof_path']) }}" alt="Bukti Transfer" class="img-fluid rounded border">
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