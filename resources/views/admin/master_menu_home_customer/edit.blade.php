@extends('layouts.admin')

@section('title', 'Edit Menu Home Customer - Admin')
@section('page_title', 'Edit Menu Home Customer')

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

            <form action="{{ route('admin.master.menu-home-customer.update', $menu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.master_menu_home_customer._form', ['menu' => $menu])
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.master.menu-home-customer.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection