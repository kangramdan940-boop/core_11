@extends('layouts.admin.master')

@section('title', 'Edit Customer - Admin')
@section('sub-title', 'Customer')
@section('breadcrumbExtra', 'Edit Customer')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.customers.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_customer._form', ['customer' => $customer])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.customers.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy-fill fs-6 me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/input-masks.init.js') }}"></script>
    <script src="{{ asset('assets/libs/air-datepicker/air-datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/air-datepicker.init.js') }}"></script>
@endsection
