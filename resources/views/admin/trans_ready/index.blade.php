@extends('layouts.admin')

@section('title', 'Transaksi Emas Ready - Admin')
@section('page_title', 'Transaksi Emas Ready')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Transaksi Emas Ready</h5>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode Trans</th>
                            <th>Customer</th>
                            <th>Agen</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total (IDR)</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readyTrans as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->kode_trans }}</td>
                                <td>{{ optional($t->customer)->full_name ?? '-' }}</td>
                                <td>{{ optional($t->agen)->name ?? '-' }}</td>
                                <td>{{ optional($t->readyStock)->kode_item ?? '-' }}</td>
                                <td>{{ $t->qty }}</td>
                                <td>{{ number_format((float)$t->total_amount, 2, ',', '.') }}</td>
                                <td>
                                    @php($st = $t->status)
                                    @if($st === 'pending_payment')
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    @elseif($st === 'paid')
                                        <span class="badge bg-success">PAID</span>
                                    @elseif($st === 'waiting_shipment')
                                        <span class="badge bg-info text-dark">WAITING SHIPMENT</span>
                                    @elseif($st === 'shipped')
                                        <span class="badge bg-primary">SHIPPED</span>
                                    @elseif($st === 'completed')
                                        <span class="badge bg-success">COMPLETED</span>
                                    @else
                                        <span class="badge bg-secondary">CANCELLED</span>
                                    @endif
                                </td>
                                <td>{{ optional($t->created_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.trans.ready.show', $t) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-3">Belum ada transaksi ready.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($readyTrans->hasPages())
                <div class="p-2">
                    {{ $readyTrans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection