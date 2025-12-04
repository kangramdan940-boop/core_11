@extends('layouts.admin')

@section('title', 'Tambah Customer - Admin')
@section('page_title', 'Tambah Customer')

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

            <form action="{{ route('admin.master.customers.store') }}" method="POST">
                @csrf

                @include('admin.master_customer._form', ['customer' => null])

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="{{ route('admin.master.customers.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
@endsection
