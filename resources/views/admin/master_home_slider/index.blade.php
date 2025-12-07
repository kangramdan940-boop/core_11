@extends('layouts.admin')

@section('title', 'Master Home Slider - Admin')
@section('page_title', 'Master Home Slider')

@section('content')
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Slider</h5>
        <a href="{{ route('admin.master.home-slider.create') }}" class="btn btn-sm btn-primary">+ Tambah Slider</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sliders as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>
                                    @if($s->image_url)
                                        <img src="{{ Str::startsWith($s->image_url, ['http://','https://']) ? $s->image_url : asset($s->image_url) }}"
                                             alt="slider" style="height:48px;object-fit:cover;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $s->title }}</td>
                                <td>
                                    <a href="{{ route('admin.master.home-slider.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.master.home-slider.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus slider ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-3">Belum ada data slider.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($sliders->hasPages())
                <div class="p-2">{{ $sliders->links() }}</div>
            @endif
        </div>
    </div>
@endsection