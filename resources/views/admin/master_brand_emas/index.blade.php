@extends('layouts.admin')

@section('title', 'Master Brand Emas - Admin')
@section('page_title', 'Master Brand Emas')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Brand Emas</h5>
        <a href="{{ route('admin.master.brand-emas.create') }}" class="btn btn-sm btn-primary">+ Tambah Brand</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-20">
            <div class="table-responsive">
                <table id="brandsTable" class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:64px;">ID</th>
                            <th class="text-center" style="width:60px;">Logo</th>
                            <th class="text-center" style="width:160px;">Kode</th>
                            <th style="min-width:200px;">Nama</th>
                            <th class="text-center" style="width:120px;">Status</th>
                            <th class="text-end text-nowrap" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $b)
                            <tr>
                                <td class="text-center">{{ $b->id }}</td>
                                <td class="text-center">
                                    @if($b->image_url)
                                        <img src="{{ Str::startsWith($b->image_url, ['http://','https://']) ? $b->image_url : asset($b->image_url) }}" alt="logo" class="rounded" style="height:36px;width:36px;object-fit:cover;background:#fff;border:1px solid #e5e7eb;">
                                    @else
                                        <div class="d-inline-flex align-items-center justify-content-center rounded" style="height:36px;width:36px;background:#e5e7eb;color:#111827;font-weight:600;">
                                            {{ Str::upper(Str::substr($b->nama_brand ?? $b->kode_brand, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border text-uppercase text-truncate" style="max-width:160px;">{{ $b->kode_brand }}</span>
                                </td>
                                <td class="text-truncate" style="max-width:220px;">{{ $b->nama_brand }}</td>
                                <td class="text-center">
                                    @if($b->is_active)
                                        <span class="badge rounded-pill bg-success">Aktif</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <a href="{{ route('admin.master.brand-emas.edit', $b) }}" class="btn btn-outline-primary btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Edit</a>
                                        <form action="{{ route('admin.master.brand-emas.destroy', $b) }}" method="POST" class="mb-0 d-inline-block" onsubmit="return confirm('Hapus brand ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-3">Belum ada data brand emas.</td></tr>
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
  $('#brandsTable').DataTable({
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    order: [[0,'desc']],
    columns: [
      { width: '64px' },
      { width: '60px', orderable: false },
      { width: '160px' },
      { width: null },
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