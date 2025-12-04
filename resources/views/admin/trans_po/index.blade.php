@extends('layouts.admin')

@section('title', 'PO Emas - Admin')
@section('page_title', 'PO Emas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar PO Emas</h5>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode PO</th>
                            <th>Customer</th>
                            <th>Agen</th>
                            <th>Total (IDR)</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pos as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->kode_po }}</td>
                                <td>{{ optional($p->customer)->full_name ?? '-' }}</td>
                                <td>{{ optional($p->agen)->name ?? '-' }}</td>
                                <td>{{ number_format((float)$p->total_amount, 2, ',', '.') }}</td>
                                <td>
                                    @php($st = $p->status)
                                    @if($st === 'pending_payment')
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    @elseif($st === 'paid')
                                        <span class="badge bg-success">PAID</span>
                                    @elseif($st === 'processing')
                                        <span class="badge bg-info text-dark">PROCESSING</span>
                                    @elseif($st === 'ready_at_agen')
                                        <span class="badge bg-primary">READY @AGEN</span>
                                    @elseif($st === 'shipped')
                                        <span class="badge bg-primary">SHIPPED</span>
                                    @elseif($st === 'completed')
                                        <span class="badge bg-success">COMPLETED</span>
                                    @else
                                        <span class="badge bg-secondary">CANCELLED</span>
                                    @endif
                                </td>
                                <td>{{ optional($p->created_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.trans.po.show', $p) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">Belum ada PO.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pos->hasPages())
                <div class="p-2">
                    {{ $pos->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection