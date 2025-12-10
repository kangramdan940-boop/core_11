@extends('layouts.admin.master')

@section('title', 'Edit Home Slider - Admin')
@section('sub-title', 'Home Slider')
@section('breadcrumbExtra', 'Edit Home Slider')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.home-slider.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.home-slider.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.master_home_slider._form', ['slider' => $slider])
                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.home-slider.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
