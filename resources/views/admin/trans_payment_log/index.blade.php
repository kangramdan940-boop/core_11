@extends('layouts.admin')

@section('title', 'Payment Logs - Admin')
@section('page_title', 'Payment Logs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Payment Log</h5>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode Payment</th>
                            <th>Ref</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Provider</th>
                            <th>Dibuat</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->kode_payment }}</td>
                                <td>{{ strtoupper($l->ref_type) }}#{{ $l->ref_id ?? '-' }}</td>
                                <td>
                                    @php($st = $l->status)
                                    @if($st === 'paid')
                                        <span class="badge bg-success">PAID</span>
                                    @elseif($st === 'pending')
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    @elseif($st === 'failed')
                                        <span class="badge bg-danger">FAILED</span>
                                    @elseif($st === 'expired')
                                        <span class="badge bg-secondary">EXPIRED</span>
                                    @else
                                        <span class="badge bg-info">REFUNDED</span>
                                    @endif
                                </td>
                                <td>{{ number_format((float)$l->amount, 2, ',', '.') }} {{ $l->currency }}</td>
                                <td>{{ $l->payment_method ?? '-' }}</td>
                                <td>{{ $l->provider ?? '-' }}</td>
                                <td>{{ optional($l->created_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.trans.payment-logs.show', $l) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">Belum ada payment log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="p-2">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection