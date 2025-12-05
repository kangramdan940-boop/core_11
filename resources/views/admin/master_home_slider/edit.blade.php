@extends('layouts.admin')

@section('title', 'Edit Home Slider - Admin')
@section('page_title', 'Edit Home Slider')

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

            <form action="{{ route('admin.master.home-slider.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.master_home_slider._form', ['slider' => $slider])
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.master.home-slider.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection