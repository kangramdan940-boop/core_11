@extends('layouts.admin')

@section('title', 'Master Menu Home Customer - Admin')
@section('page_title', 'Master Menu Home Customer')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Menu</h5>
        <a href="{{ route('admin.master.menu-home-customer.create') }}" class="btn btn-sm btn-primary">+ Tambah Menu</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Label</th>
                            <th>Path URL</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $m)
                            <tr>
                                <td>{{ $m->id }}</td>
                                <td>
                                    @if($m->image)
                                        <img src="{{ Str::startsWith($m->image, ['http://','https://']) ? $m->image : asset('storage/' . $m->image) }}"
                                             alt="menu" style="height:48px;object-fit:cover;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $m->label }}</td>
                                <td>{{ $m->path_url ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.master.menu-home-customer.edit', $m) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.master.menu-home-customer.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3">Belum ada data menu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($menus->hasPages())
                <div class="p-2">{{ $menus->links() }}</div>
            @endif
        </div>
    </div>
@endsection