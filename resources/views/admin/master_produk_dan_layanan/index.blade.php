@extends('layouts.admin')

@section('title', 'Master Produk & Layanan - Admin')
@section('page_title', 'Master Produk & Layanan')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Produk & Layanan</h5>
        <a href="{{ route('admin.master.produk-layanan.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-20">
            <div class="table-responsive">
                <table id="produkTable" class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:64px;">ID</th>
                            <th class="text-center" style="width:60px;">Gambar</th>
                            <th style="min-width:160px;">Gramasi</th>
                            <th style="min-width:160px;">Harga Hari Ini</th>
                            <th class="text-center" style="width:140px;">Ready/PO</th>
                            <th class="text-center" style="width:120px;">Status</th>
                            <th class="text-end text-nowrap" style="width:160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i)
                            <tr>
                                <td class="text-center">{{ $i->id }}</td>
                                <td class="text-center">
                                    @if($i->image_produk)
                                        <img src="{{ Str::startsWith($i->image_produk, ['http://','https://']) ? $i->image_produk : asset($i->image_produk) }}"
                                             alt="produk" class="rounded" style="height:36px;width:36px;object-fit:cover;background:#fff;border:1px solid #e5e7eb;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ optional($i->gramasi)->gramasi ? number_format($i->gramasi->gramasi, 3).' g' : '-' }}</td>
                                <td>{{ number_format((float)$i->harga_hariini, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $i->is_allow_ready ? 'bg-success' : 'bg-secondary' }}">Ready</span>
                                    <span class="badge rounded-pill {{ $i->is_allow_po ? 'bg-success' : 'bg-secondary' }}">PO</span>
                                </td>
                                <td class="text-center">
                                    @if($i->status === 'active')
                                        <span class="badge rounded-pill bg-success">Aktif</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <a href="{{ route('admin.master.produk-layanan.edit', $i) }}" class="btn btn-outline-primary btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Edit</a>
                                        <form action="{{ route('admin.master.produk-layanan.destroy', $i) }}" method="POST" class="mb-0 d-inline-block" onsubmit="return confirm('Hapus item ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-3">Belum ada data produk & layanan.</td></tr>
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
  $('#produkTable').DataTable({
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    order: [[0,'desc']],
    columns: [
      { width: '64px' },
      { width: '60px', orderable: false },
      { width: '160px' },
      { width: '160px' },
      { width: '140px', orderable: false },
      { width: '120px' },
      { width: '160px', orderable: false }
    ],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
    responsive: true
  });
});
</script>
@endpush
@endsection