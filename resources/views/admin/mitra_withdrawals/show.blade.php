@extends('layouts.admin.master')

@section('title', 'Detail WD Mitra - Admin')
@section('sub-title', 'WD Mitra')
@section('breadcrumbExtra', 'Detail WD')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.mitra-withdrawals.index'))

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trans.mitra-withdrawals.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.trans.mitra-withdrawals.update-status', $withdrawal) }}" method="POST" class="d-flex align-items-center gap-2">
                @csrf
                <div class="input-group" style="min-width:280px;">
                    <span class="input-group-text"><i class="ri-flag-2-line"></i></span>
                    <select name="status" class="form-select">
                        @foreach ($allowedStatuses as $st)
                            <option value="{{ $st }}" {{ $withdrawal->status === $st ? 'selected' : '' }}>{{ strtoupper($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update Status</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3 fs-5"># Ringkasan WD</h6>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div><strong>ID</strong><br>{{ $withdrawal->id }}</div>
                    <div class="mt-2"><strong>Mitra</strong><br>{{ optional($withdrawal->mitra)->nama_lengkap ?? '-' }}</div>
                    <div class="mt-2"><strong>No Rekening Tujuan</strong><br>{{ $withdrawal->target_account_no ?? '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                    <div><strong>Amount</strong><br>Rp {{ number_format((float)$withdrawal->amount, 2, ',', '.') }}</div>
                    @php
                        $s = $withdrawal->status;
                        $badge = 'text-bg-secondary';
                        if ($s === 'requested') { $badge = 'text-bg-warning'; }
                        elseif ($s === 'processing') { $badge = 'text-bg-info'; }
                        elseif ($s === 'checking') { $badge = 'text-bg-primary'; }
                        elseif ($s === 'completed') { $badge = 'text-bg-success'; }
                        elseif ($s === 'canceled') { $badge = 'text-bg-secondary'; }
                    @endphp
                    <div class="mt-2"><strong>Status</strong><br><span class="badge rounded-pill {{ $badge }}">{{ strtoupper($s) }}</span></div>
                    <div class="mt-2"><strong>Requested</strong><br>{{ optional($withdrawal->requested_at)->format('Y-m-d H:i') }}</div>
                    <div class="mt-2"><strong>Processed</strong><br>{{ optional($withdrawal->processed_at)->format('Y-m-d H:i') }}</div>
                    <div class="mt-2"><strong>Completed</strong><br>{{ optional($withdrawal->completed_at)->format('Y-m-d H:i') }}</div>
                </div>
            </div>
            <div class="mt-3">
                <strong>Catatan Admin</strong>
                <div class="border rounded p-2" style="min-height: 60px;">{{ $withdrawal->admin_notes ?? '-' }}</div>
            </div>
            <div class="mt-3">
                <strong>Bukti Pembayaran</strong><br>
                @if(!empty($withdrawal->payment_proof_url))
                    @php($ext = pathinfo($withdrawal->payment_proof_url, PATHINFO_EXTENSION))
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                        <img src="{{ asset($withdrawal->payment_proof_url) }}" alt="Bukti Pembayaran" class="img-fluid rounded border" style="max-width: 420px;">
                    @else
                        <a href="{{ asset($withdrawal->payment_proof_url) }}" target="_blank" rel="noopener">Lihat Bukti ({{ strtoupper($ext) }})</a>
                    @endif
                @else
                    <span class="text-muted">Belum ada bukti pembayaran.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <h6 class="mb-2">Update Status & Catatan</h6>
                    <form action="{{ route('admin.trans.mitra-withdrawals.update-status', $withdrawal) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label for="status" class="form-label mb-1">Status</label>
                            <select id="status" name="status" class="form-select">
                                @foreach ($allowedStatuses as $st)
                                    <option value="{{ $st }}" {{ $withdrawal->status === $st ? 'selected' : '' }}>
                                        {{ strtoupper($st) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="admin_notes" class="form-label mb-1">Catatan Admin</label>
                            <textarea id="admin_notes" name="admin_notes" class="form-control" rows="4" placeholder="Catatan internal...">{{ old('admin_notes', $withdrawal->admin_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                    </form>
                </div>
                <div class="col-12 col-md-6">
                    <h6 class="mb-2">Unggah Bukti Pembayaran</h6>
                    <form action="{{ route('admin.trans.mitra-withdrawals.upload-proof', $withdrawal) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-upload fs-6 me-1"></i> Unggah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection