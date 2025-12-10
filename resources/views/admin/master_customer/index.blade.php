@extends('layouts.admin')

@section('title', 'Master Customer - Admin')
@section('page_title', 'Master Customer')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2">
            {{ $errors->first() }}
        </div>
    @endif
     

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Customer</h5>
        <a href="{{ route('admin.master.customers.create') }}" class="btn btn-sm btn-primary">
            + Tambah Customer
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-20">
            <div class="table-responsive">
                <table id="customersTable" class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:64px;">ID</th>
                            <th style="min-width:200px;">Nama Lengkap</th>
                            <th style="min-width:200px;">Email</th>
                            <th style="min-width:160px;">WhatsApp</th>
                            <th style="min-width:160px;">Kota</th>
                            <th class="text-center" style="width:120px;">Status</th>
                            <th class="text-end text-nowrap" style="width:160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $c)
                            <tr>
                                <td class="text-center">{{ $c->id }}</td>
                                <td>{{ $c->full_name }}</td>
                                <td>{{ $c->email }}</td>
                                <td>{{ $c->phone_wa }}</td>
                                <td>{{ $c->kota }}</td>
                                <td class="text-center">
                                    @if($c->is_active)
                                        <span class="badge rounded-pill bg-success">Aktif</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <a href="{{ route('admin.master.customers.edit', $c) }}" class="btn btn-outline-primary btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Edit</a>
                                        <form action="{{ route('admin.master.customers.destroy', $c) }}" method="POST" class="mb-0 d-inline-block" onsubmit="return confirm('Hapus customer ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm px-2 d-inline-flex align-items-center" style="height:28px;">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    Belum ada data customer.
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
  $('#customersTable').DataTable({
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    order: [[0,'desc']],
    columns: [
      { width: '64px' },
      { width: '200px' },
      { width: '200px' },
      { width: '160px' },
      { width: '160px' },
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
