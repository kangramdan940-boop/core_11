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
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>WhatsApp</th>
                            <th>Kota</th>
                            <th>Status</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->full_name }}</td>
                                <td>{{ $c->email }}</td>
                                <td>{{ $c->phone_wa }}</td>
                                <td>{{ $c->kota }}</td>
                                <td>
                                    @if($c->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.master.customers.edit', $c) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.master.customers.destroy', $c) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus customer ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>
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

            @if ($customers->hasPages())
                <div class="p-2">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
