@extends('layouts.admin.master')

@section('title', 'Management Payment - Admin')
@section('sub-title', 'Management Payment')
@section('breadcrumbExtra', 'Konfigurasi Pembayaran')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.payment-settings.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if($setting)
                <div class="table-responsive">
                    <table class="table table-bordered mb-4">
                        <tbody>
                        <tr>
                            <th style="width:260px;">Nomor Rekening</th>
                            <td>{{ $setting->rekening_nomor }}</td>
                        </tr>
                        <tr>
                            <th>Nama Bank</th>
                            <td>{{ $setting->bank_nama }}</td>
                        </tr>
                        <tr>
                            <th>Atas Nama</th>
                            <td>{{ $setting->rekening_atas_nama }}</td>
                        </tr>
                        <tr>
                            <th>Kadaluarsa Pembayaran (menit)</th>
                            <td>{{ $setting->expired_minutes }}</td>
                        </tr>
                        <tr>
                            <th>Petunjuk Konfirmasi Pembayaran</th>
                            <td>{!! nl2br(e($setting->konfirmasi_petunjuk)) !!}</td>
                        </tr>
                        <tr>
                            <th>Syarat dan Ketentuan</th>
                            <td>{!! nl2br(e($setting->syarat_ketentuan)) !!}</td>
                        </tr>
                        <tr>
                            <th>Informasi Jasa Titip dan Ketentuan</th>
                            <td>{!! nl2br(e($setting->jasa_titip_informasi)) !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.master.payment-settings.edit', $setting) }}" class="btn btn-light-primary">
                        <i class="ri-pencil-line"></i> Edit
                    </a>
                    <form action="{{ route('admin.master.payment-settings.destroy', $setting) }}" method="POST" onsubmit="return confirm('Hapus konfigurasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light-danger"><i class="ri-delete-bin-line"></i> Hapus</button>
                    </form>
                </div>
            @else
                <p class="text-muted">Belum ada konfigurasi pembayaran.</p>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.master.payment-settings.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg fs-6 me-1"></i> Tambah Data
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection