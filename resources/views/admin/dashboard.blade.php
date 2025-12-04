@extends('layouts.admin')

@section('title', 'Dashboard Admin - Jasa Emas')
@section('page_title', 'Dashboard Admin')

@section('content')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">PO Emas</h6>
                    <p class="card-text small text-muted">
                        Nanti di sini bisa tampil total PO aktif, menunggu proses, dsb.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Emas Ready</h6>
                    <p class="card-text small text-muted">
                        Ringkasan stok emas ready milik agen.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Cicilan Emas</h6>
                    <p class="card-text small text-muted">
                        Ringkasan kontrak cicilan aktif / jatuh tempo.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
