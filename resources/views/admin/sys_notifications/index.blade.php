@extends('layouts.admin.master')

@section('title', 'Notifikasi')
@section('page_title', 'Notifikasi')

@section('content')
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form action="{{ route('admin.notifications.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Channel</label>
                <select name="channel" class="form-select">
                    <option value="">Semua</option>
                    @foreach($channels as $c)
                        <option value="{{ $c }}" {{ ($filters['channel'] ?? '') === $c ? 'selected' : '' }}>{{ strtoupper($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ref Type</label>
                <select name="refType" class="form-select">
                    <option value="">Semua</option>
                    @foreach($refTypes as $t)
                        <option value="{{ $t }}" {{ ($filters['refType'] ?? '') === $t ? 'selected' : '' }}>{{ strtoupper($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ ($filters['status'] ?? '') === $s ? 'selected' : '' }}>{{ strtoupper($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dibaca?</label>
                <select name="isRead" class="form-select">
                    <option value="">Semua</option>
                    <option value="1" {{ ($filters['isRead'] ?? '') === '1' ? 'selected' : '' }}>Sudah</option>
                    <option value="0" {{ ($filters['isRead'] ?? '') === '0' ? 'selected' : '' }}>Belum</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Sejak</label>
                <input type="date" name="since" value="{{ $filters['since'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Hingga</label>
                <input type="date" name="until" value="{{ $filters['until'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Cari (judul/pesan)</label>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Ketik kata kunci">
            </div>
            <div class="col-md-6 text-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover align-middle table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Channel</th>
                    <th>Ref</th>
                    <th>User</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Dibaca</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $n)
                    <tr>
                        <td>{{ $n->id }}</td>
                        <td>
                            @if($n->sent_at)
                                {{ $n->sent_at->format('Y-m-d H:i') }}
                            @elseif($n->created_at)
                                {{ $n->created_at->format('Y-m-d H:i') }}
                            @endif
                        </td>
                        <td>{{ strtoupper($n->channel ?? 'system') }}</td>
                        <td>{{ ($n->ref_type ?? '-') }} @if($n->ref_id)#{{ $n->ref_id }}@endif</td>
                        <td>{{ $n->user?->name ?? '-' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $n->title }}</div>
                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit($n->message, 120) }}</div>
                        </td>
                        <td>
                            @php $st = strtolower($n->status ?? 'sent'); @endphp
                            <span class="badge text-bg-{{ $st === 'failed' ? 'danger' : ($st === 'pending' ? 'warning' : 'success') }}">{{ strtoupper($st) }}</span>
                        </td>
                        <td>
                            <span class="badge text-bg-{{ $n->is_read ? 'success' : 'secondary' }}">{{ $n->is_read ? 'Sudah' : 'Belum' }}</span>
                        </td>
                        <td>
                            @php
                                $detailUrl = null;
                                if ($n->ref_type === 'keranjang' && $n->ref_id) {
                                    $detailUrl = route('admin.trans.keranjang.show', $n->ref_id);
                                } elseif ($n->ref_type === 'po' && $n->ref_id) {
                                    $detailUrl = route('admin.trans.po.show', $n->ref_id);
                                } elseif ($n->ref_type === 'ready' && $n->ref_id) {
                                    $detailUrl = route('admin.trans.ready.show', $n->ref_id);
                                } elseif ($n->ref_type === 'cicilan' && $n->ref_id) {
                                    $detailUrl = route('admin.trans.cicilan.show', $n->ref_id);
                                }
                            @endphp
                            @if($detailUrl)
                                <a href="{{ $detailUrl }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada notifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection