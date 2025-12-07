@extends('layouts.admin')

@section('title', 'Edit Brand Emas - Admin')
@section('page_title', 'Edit Brand Emas')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Edit Brand</h6>
            <a href="{{ route('admin.master.brand-emas.index') }}" class="btn btn-outline-secondary btn-sm px-2" style="height:28px;">← Kembali</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li style="font-size:0.85rem;">{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.master.brand-emas.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('admin.master_brand_emas._form', ['brand' => $brand])

                @if($brand->image_url)
                    <div class="mb-3">
                        <img src="{{ Str::startsWith($brand->image_url, ['http://','https://']) ? $brand->image_url : asset('storage/' . $brand->image_url) }}" alt="logo" class="rounded" style="height:48px;width:48px;object-fit:cover;background:#fff;border:1px solid #e5e7eb;">
                    </div>
                @endif

                <div class="d-flex justify-content-end align-items-center gap-2 border-top pt-3 mt-3">
                    <button type="submit" class="btn btn-primary btn-sm px-3" style="height:32px;">Update</button>
                    <a href="{{ route('admin.master.brand-emas.index') }}" class="btn btn-secondary btn-sm px-3" style="height:32px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection