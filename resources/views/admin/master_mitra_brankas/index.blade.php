@extends('layouts.admin')

@section('title', 'Master Mitra Brankas - Admin')
@section('page_title', 'Master Mitra Brankas')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Mitra Brankas</h5>
        <a href="{{ route('admin.master.mitra-brankas.create') }}" class="btn btn-sm btn-primary">
            + Tambah Mitra
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-20">
            <div class="table-responsive">
                <table id="mitraTable" class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:64px;">ID</th>
                            <th style="min-width:200px;">Nama Lengkap</th>
                            <th style="min-width:200px;">Email</th>
                            <th style="min-width:160px;">WhatsApp</th>
                            <th class="text-center" style="width:140px;">Kode Mitra</th>
                            <th style="min-width:160px;">Platform</th>
                            <th class="text-end" style="min-width:160px;">Limit Harian (g)</th>
                            <th class="text-end" style="width:120px;">Komisi (%)</th>
                            <th class="text-center" style="width:120px;">Status</th>
                            <th class="text-end text-nowrap" style="width:160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mitras as $m)
                            <tr>
                                <td class="text-center">{{ $m->id }}</td>
                                <td>{{ $m->nama_lengkap }}</td>
                                <td>{{ $m->email }}</td>
                                <td>{{ $m->phone_wa }}</td>
                                <td class="text-center"><span class="badge bg-light text-dark border text-uppercase">{{ $m->kode_mitra }}</span></td>
                                <td>{{ $m->platform }}</td>
                                <td class="text-end">{{ number_format((float)$m->harian_limit_gram, 3, ',', '.') }}</td>
                                <td class="text-end">{{ number_format((float)$m->komisi_persen, 2, ',', '.') }} %</td>
                                <td class="text-center">
                                    @if($m->is_active)
                                        <span class="badge rounded-pill bg-success">Aktif</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <a href="{{ route('admin.master.mitra-brankas.edit', $m) }}" class="btn btn-outline-primary btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Edit</a>
                                        <form action="{{ route('admin.master.mitra-brankas.destroy', $m) }}" method="POST" class="mb-0 d-inline-block" onsubmit="return confirm('Hapus mitra ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Hapus</button>
                                        </form>
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
  $('#mitraTable').DataTable({
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    order: [[0,'desc']],
    columns: [
      { width: '64px' },
      { width: '200px' },
      { width: '200px' },
      { width: '160px' },
      { width: '140px' },
      { width: '160px' },
      { width: '160px' },
      { width: '120px' },
      { width: '120px', orderable: false },
      { width: '160px', orderable: false }
    ],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
    responsive: true
  });
});
</script>
@endpush
@endsection