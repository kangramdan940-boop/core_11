@extends('layouts.admin.master')

@section('title', 'Edit Trans Cicilan Emas - Admin')
@section('sub-title', 'Trans Cicilan Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan-emas.index'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Trans #{{ $record->id }}</h5>
            <a href="{{ route('admin.trans.cicilan-emas.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.trans.cicilan-emas.update', $record) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.trans_cicilan_emas._form')
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit"><i class="ri-check-line"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection