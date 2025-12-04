@extends('layouts.admin')

@section('title', 'Pembayaran Cicilan - Admin')
@section('page_title', 'Pembayaran Cicilan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Pembayaran Cicilan</h5>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kontrak</th>
                            <th>Customer</th>
                            <th>Cicilan Ke</th>
                            <th>Due Date</th>
                            <th>Amount Due</th>
                            <th>Status</th>
                            <th>Paid At</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ optional($p->kontrak)->kode_kontrak ?? '-' }}</td>
                                <td>{{ optional(optional($p->kontrak)->customer)->full_name ?? '-' }}</td>
                                <td>{{ $p->cicilan_ke }}</td>
                                <td>{{ optional($p->due_date)->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ number_format((float)$p->amount_due, 2, ',', '.') }}</td>
                                <td>{{ strtoupper($p->status) }}</td>
                                <td>{{ optional($p->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.trans.cicilan-payments.show', $p) }}"
                                       class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">Belum ada pembayaran cicilan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($payments->hasPages())
                <div class="p-2">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection