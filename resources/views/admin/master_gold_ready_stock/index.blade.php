@extends('layouts.admin')

@section('title', 'Master Stok Emas Ready - Admin')
@section('page_title', 'Master Stok Emas Ready')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Stok Emas Ready</h5>
        <a href="{{ route('admin.master.ready-stocks.create') }}" class="btn btn-sm btn-primary">
            + Tambah Stok
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-20">
            <div class="table-responsive">
                <table id="readyStocksTable" class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:64px;">ID</th>
                            <th class="text-center" style="width:160px;">Kode Item</th>
                            <th style="min-width:160px;">Brand</th>
                            <th class="text-end" style="min-width:140px;">Gramasi (g)</th>
                            <th style="min-width:160px;">Agen</th>
                            <th style="min-width:140px;">Kondisi</th>
                            <th class="text-center" style="width:120px;">Status</th>
                            <th class="text-end" style="min-width:160px;">Harga Jual Fix</th>
                            <th class="text-end text-nowrap" style="width:160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $s)
                            <tr>
                                <td class="text-center">{{ $s->id }}</td>
                                <td class="text-center"><span class="badge bg-light text-dark border text-uppercase">{{ $s->kode_item }}</span></td>
                                <td class="text-uppercase">{{ $s->brand }}</td>
                                <td class="text-end">{{ number_format((float)$s->gramasi, 3, ',', '.') }}</td>
                                <td>{{ optional($s->agen)->name ?? '-' }}</td>
                                <td>{{ ucfirst($s->kondisi_barang) }}</td>
                                <td class="text-center">
                                    @if($s->status === 'available')
                                        <span class="badge rounded-pill bg-success">Available</span>
                                    @elseif($s->status === 'reserved')
                                        <span class="badge rounded-pill bg-warning text-dark">Reserved</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">Sold</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ $s->harga_jual_fix !== null ? number_format((float)$s->harga_jual_fix, 2, ',', '.') : '-' }}</td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <a href="{{ route('admin.master.ready-stocks.edit', $s) }}" class="btn btn-outline-primary btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Edit</a>
                                        <form action="{{ route('admin.master.ready-stocks.destroy', $s) }}" method="POST" class="mb-0 d-inline-block" onsubmit="return confirm('Hapus stok ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">
                                    Belum ada data stok emas ready.
                                </td>
                            </tr>
                        @endforelse
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
  $('#readyStocksTable').DataTable({
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    order: [[0,'desc']],
    columns: [
      { width: '64px' },
      { width: '160px' },
      { width: '160px' },
      { width: '140px' },
      { width: '160px' },
      { width: '140px' },
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