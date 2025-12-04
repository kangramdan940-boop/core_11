@extends('layouts.admin')

@section('title', 'Cicilan Emas - Admin')
@section('page_title', 'Cicilan Emas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Kontrak Cicilan</h5>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kode Kontrak</th>
                            <th>Customer</th>
                            <th>Agen</th>
                            <th>Total (IDR)</th>
                            <th>Tenor</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contracts as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->kode_kontrak }}</td>
                                <td>{{ optional($c->customer)->full_name ?? '-' }}</td>
                                <td>{{ optional($c->agen)->name ?? '-' }}</td>
                                <td>{{ number_format((float)$c->harga_total_kontrak, 2, ',', '.') }}</td>
                                <td>{{ $c->tenor_bulan }} bln</td>
                                <td>{{ strtoupper($c->status) }}</td>
                                <td>{{ optional($c->created_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.trans.cicilan.show', $c) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">Belum ada kontrak cicilan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($contracts->hasPages())
                <div class="p-2">
                    {{ $contracts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection