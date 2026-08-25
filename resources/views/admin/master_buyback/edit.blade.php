@extends('layouts.admin.master')

@section('title', 'Edit Buyback - Admin')
@section('sub-title', 'Buyback')
@section('breadcrumbExtra', 'Edit Buyback')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.buyback.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.buyback.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_buyback._form', ['item' => $item])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.buyback.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
