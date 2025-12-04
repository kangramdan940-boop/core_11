@extends('layouts.admin')

@section('title', 'Edit Customer - Admin')
@section('page_title', 'Edit Customer')

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
            <form action="{{ route('admin.master.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_customer._form', ['customer' => $customer])

                <button type="submit" class="btn btn-primary">
                    Update
                </button>
                <a href="{{ route('admin.master.customers.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
@endsection
