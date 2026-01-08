@extends('layouts.admin.master')

@section('title', 'Edit Faktur Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Edit Faktur Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.faktur.index'))

@section('content')
    @include('admin.master_faktur._form', [
        'document' => $document,
        'action' => route('admin.master.faktur.update', $document),
        'method' => 'PUT'
    ])
@endsection