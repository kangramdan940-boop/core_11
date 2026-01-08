@extends('layouts.admin.master')

@section('title', 'Tambah Faktur Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Tambah Faktur Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.faktur.index'))

@section('content')
    @include('admin.master_faktur._form', [
        'document' => $document,
        'action' => route('admin.master.faktur.store')
    ])
@endsection