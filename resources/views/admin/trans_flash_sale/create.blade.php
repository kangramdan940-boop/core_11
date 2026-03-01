@extends('layouts.admin.master')

@section('title', 'Buat Flash Sale Order - Admin')
@section('sub-title', 'Transaksi')
@section('breadcrumbExtra', 'Flash Sale Order')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.flash-sale-orders.index'))

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.trans.flash-sale-orders.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.trans.flash-sale-orders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Barang Flash Sale</label>
                        <select name="flash_sale_id" class="form-select @error('flash_sale_id') is-invalid @enderror">
                            <option value="">— Pilih —</option>
                            @foreach(($items ?? []) as $it)
                                <option value="{{ $it->id }}" {{ old('flash_sale_id') == $it->id ? 'selected' : '' }}>
                                    {{ $it->item_name }} ({{ number_format((float)$it->harga_jual, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('flash_sale_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Pembeli</label>
                        <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}">
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>



                    <div class="col-md-4">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Alamat Pengiriman</label>
                        <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>





                    <div class="col-md-6">
                        <label class="form-label">Bukti Bayar (jpg/png/pdf)</label>
                        <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                        @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Bukti Paket (jpg/png/pdf)</label>
                        <input type="file" name="package_proof" class="form-control @error('package_proof') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                        @error('package_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection