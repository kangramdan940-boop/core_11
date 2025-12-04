@extends('layouts.admin')

@section('title', 'Edit Stok Emas Ready - Admin')
@section('page_title', 'Edit Stok Emas Ready')

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

            <form action="{{ route('admin.master.ready-stocks.update', $stock) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_gold_ready_stock._form', ['stock' => $stock, 'agens' => $agens])

                <button type="submit" class="btn btn-primary">
                    Update
                </button>
                <a href="{{ route('admin.master.ready-stocks.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
@endsection