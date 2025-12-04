@extends('layouts.admin')

@section('title', 'Tambah Mitra Brankas - Admin')
@section('page_title', 'Tambah Mitra Brankas')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li style="font-size: 0.85rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.master.mitra-brankas.store') }}" method="POST">
                @csrf

                @include('admin.master_mitra_brankas._form', ['mitra' => null])

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="{{ route('admin.master.mitra-brankas.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
@endsection