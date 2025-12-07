@extends('layouts.admin')

@section('title', 'PO Emas - Admin')
@section('page_title', 'PO Emas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar PO Emas</h5>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-20">
            <div class="table-responsive">
                <table id="poTable" class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:64px;">ID</th>
                            <th class="text-center" style="width:160px;">Kode PO</th>
                            <th style="min-width:200px;">Customer</th>
                            <th style="min-width:160px;">Agen</th>
                            <th class="text-end" style="min-width:160px;">Total (IDR)</th>
                            <th class="text-center" style="width:120px;">Status</th>
                            <th style="min-width:160px;">Dibuat</th>
                            <th class="text-end text-nowrap" style="width:160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pos as $p)
                            <tr>
                                <td class="text-center">{{ $p->id }}</td>
                                <td class="text-center"><span class="badge bg-light text-dark border text-uppercase">{{ $p->kode_po }}</span></td>
                                <td>{{ optional($p->customer)->full_name ?? '-' }}</td>
                                <td>{{ optional($p->agen)->name ?? '-' }}</td>
                                <td class="text-end">{{ number_format((float)$p->total_amount, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    @php($st = $p->status)
                                    @if($st === 'pending_payment')
                                        <span class="badge rounded-pill bg-warning text-dark">PENDING</span>
                                    @elseif($st === 'paid')
                                        <span class="badge rounded-pill bg-success">PAID</span>
                                    @elseif($st === 'processing')
                                        <span class="badge rounded-pill bg-info text-dark">PROCESSING</span>
                                    @elseif($st === 'ready_at_agen')
                                        <span class="badge rounded-pill bg-primary">READY @AGEN</span>
                                    @elseif($st === 'shipped')
                                        <span class="badge rounded-pill bg-primary">SHIPPED</span>
                                    @elseif($st === 'completed')
                                        <span class="badge rounded-pill bg-success">COMPLETED</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">CANCELLED</span>
                                    @endif
                                </td>
                                <td>{{ optional($p->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <a href="{{ route('admin.trans.po.show', $p) }}" class="btn btn-outline-primary btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Detail</a>
                                    </div>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function(){
  $('#poTable').DataTable({
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    order: [[0,'desc']],
    columns: [
      { width: '64px' },
      { width: '160px' },
      { width: '200px' },
      { width: '160px' },
      { width: '160px' },
      { width: '120px', orderable: false },
      { width: '160px' },
      { width: '160px', orderable: false }
    ],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
    responsive: true
  });
});
</script>
@endpush
@endsection