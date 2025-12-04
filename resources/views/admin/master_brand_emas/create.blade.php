@extends('layouts.admin')

@section('title', 'Tambah Brand Emas - Admin')
@section('page_title', 'Tambah Brand Emas')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li style="font-size:0.85rem;">{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.master.brand-emas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.master_brand_emas._form', ['brand' => null])
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.master.brand-emas.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection