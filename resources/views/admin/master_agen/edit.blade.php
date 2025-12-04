@extends('layouts.admin')

@section('title', 'Master Agen — Edit')
@section('page_title', 'Master Agen — Edit')

@section('content')
    <div class="mb-2">
        <small class="text-muted">View: resources/views/admin/master_agen/edit.blade.php • Route: admin.master.agens.edit • URL: /admin/master/agens/{agen}/edit</small>
    </div>

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

            <form action="{{ route('admin.master.agens.update', $agen) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.master_agen._form', ['agen' => $agen])

                <button type="submit" class="btn btn-primary">
                    Update
                </button>
                <a href="{{ route('admin.master.agens.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
@endsection