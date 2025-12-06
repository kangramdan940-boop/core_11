<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css')}}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css')}}" />
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png')}}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png')}}" />
    <title>Beli Ready || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.ready.index') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Beli Ready</h3>
    </div>
    <div class="app-content style-3">
        <div class="tf-container">

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2">
            <div class="fw-bold mb-1">Terjadi kesalahan:</div>
            <ul class="mb-0 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h6 mb-3">Beli Emas Ready</h1>

            <div class="mb-3">
                <strong>Item</strong>: {{ $stock->kode_item }} • {{ strtoupper($stock->brand) }} • {{ number_format((float)$stock->gramasi, 3, ',', '.') }} g
                <br>
                <strong>Harga/jual</strong>: {{ number_format((float)($stock->harga_jual_fix ?? $stock->harga_jual_minimal ?? 0), 2, ',', '.') }} IDR
            </div>

            <form action="{{ route('customer.ready.store') }}" method="POST" class="mt-10">
                @csrf
                <input type="hidden" name="ready_stock_id" value="{{ encrypt((string) $stock->id) }}">
                <div class="form-field form-2 mt-24">
                    <div class="label h7">Tipe Penerimaan</div>
                    <fieldset class="mt-12">
                        <select name="delivery_type" class="form-select">
                            <option value="ship" @selected(old('delivery_type')=='ship')>Dikirim</option>
                            <option value="pickup" @selected(old('delivery_type')=='pickup')>Ambil di Agen</option>
                        </select>
                    </fieldset>
                    <div class="small text-muted mt-8">Isi data pengiriman jika memilih "Dikirim".</div>
                </div>

                @php $isShip = old('delivery_type') === 'ship'; @endphp
                <div id="shippingFields" style="{{ $isShip ? '' : 'display:none;' }}">
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">Nama Penerima</div>
                        <fieldset class="mt-12">
                            <input type="text" name="shipping_name" class="form-control @error('shipping_name') is-invalid @enderror" value="{{ old('shipping_name', $customer->full_name ?? '') }}" {{ $isShip ? 'required' : '' }}>
                            @error('shipping_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </fieldset>
                    </div>
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">HP Penerima</div>
                        <fieldset class="mt-12">
                            <input type="text" name="shipping_phone" class="form-control @error('shipping_phone') is-invalid @enderror" value="{{ old('shipping_phone', $customer->phone_wa ?? '') }}" {{ $isShip ? 'required' : '' }}>
                            @error('shipping_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </fieldset>
                    </div>
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">Alamat</div>
                        <fieldset class="mt-12">
                            <input type="text" name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" value="{{ old('shipping_address', $customer->address_line ?? '') }}" {{ $isShip ? 'required' : '' }}>
                            @error('shipping_address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </fieldset>
                    </div>
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">Kota</div>
                        <fieldset class="mt-12">
                            <input type="text" name="shipping_city" class="form-control @error('shipping_city') is-invalid @enderror" value="{{ old('shipping_city', $customer->kota ?? '') }}" {{ $isShip ? 'required' : '' }}>
                            @error('shipping_city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </fieldset>
                    </div>
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">Provinsi</div>
                        <fieldset class="mt-12">
                            <input type="text" name="shipping_province" class="form-control @error('shipping_province') is-invalid @enderror" value="{{ old('shipping_province', $customer->provinsi ?? '') }}" {{ $isShip ? 'required' : '' }}>
                            @error('shipping_province')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </fieldset>
                    </div>
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">Kode Pos</div>
                        <fieldset class="mt-12">
                            <input type="text" name="shipping_postal_code" class="form-control @error('shipping_postal_code') is-invalid @enderror" value="{{ old('shipping_postal_code', $customer->kode_pos ?? '') }}" {{ $isShip ? 'required' : '' }}>
                            @error('shipping_postal_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </fieldset>
                    </div>
                </div>
                <div class="form-field form-2 mt-24">
                    <div class="label h7">Catatan</div>
                    <fieldset class="mt-12">
                        <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                    </fieldset>
                </div>

                @php $hargaItem = (float)($stock->harga_jual_fix ?? $stock->harga_jual_minimal ?? 0); @endphp
                <div class="card-body mt-20">
                    <div class="alert alert-primary light alert-dismissible fade show mb-10" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3.27 15.084c.693-1.457 1.04-2.186 1.644-2.607q.074-.051.15-.098C5.691 12 6.461 12 8 12s2.308 0 2.937.38q.075.046.149.096c.604.422.951 1.15 1.645 2.608c1.034 2.173 1.55 3.26 1.112 4.058l-.052.089C13.308 20 12.161 20 9.87 20H6.13c-2.292 0-3.439 0-3.922-.77l-.052-.088c-.439-.798.078-1.885 1.112-4.058Z"/><path stroke-linecap="round" d="M14.547 12.02c.396-.02.869-.02 1.452-.02c1.538 0 2.308 0 2.937.38q.075.046.149.096c.604.422.951 1.15 1.645 2.608c1.034 2.173 1.55 3.26 1.112 4.058q-.024.045-.052.089c-.483.769-1.63.769-3.922.769h-1.129m.868-11a41 41 0 0 0-.876-1.916c-.694-1.457-1.04-2.186-1.645-2.607a3 3 0 0 0-.15-.098C14.309 4 13.54 4 12 4s-2.308 0-2.937.38q-.075.045-.15.096c-.603.422-.95 1.15-1.644 2.608c-.361.76-.66 1.388-.876 1.916"/></g></svg>
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center font-monospace">
                                <span class="fw-semibold">Estimasi Bayar</span>
                                <span id="estimasiTotal" class="fw-bold text-success fs-3 lh-1">{{ 'Rp ' . number_format($hargaItem, 0, ',', '.') }}</span>
                            </div>
                            <div class="small text-muted fw-semibold">Per item: <span id="estimasiHarga">{{ 'Rp ' . number_format($hargaItem, 0, ',', '.') }}</span> × <span id="estimasiQty">1</span> pcs</div>
                        </div>
                    </div>
                </div>

                <div class="card-body mt-20">
                    <div class="alert alert-light alert-dismissible fade show" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M11.25 12.75V7.50001C11.25 7.3011 11.329 7.11033 11.4697 6.96968C11.6103 6.82903 11.8011 6.75001 12 6.75001C12.1989 6.75001 12.3897 6.82903 12.5303 6.96968C12.671 7.11033 12.75 7.3011 12.75 7.50001V12.75C12.75 12.9489 12.671 13.1397 12.5303 13.2803C12.3897 13.421 12.1989 13.5 12 13.5C11.8011 13.5 11.6103 13.421 11.4697 13.2803C11.329 13.1397 11.25 12.9489 11.25 12.75ZM21.75 8.58282V15.4172C21.7506 15.6143 21.7121 15.8095 21.6366 15.9915C21.5611 16.1735 21.4502 16.3387 21.3103 16.4775L16.4775 21.3103C16.3387 21.4502 16.1735 21.5611 15.9915 21.6366C15.8095 21.7121 15.6143 21.7506 15.4172 21.75H8.58282C8.38577 21.7506 8.19055 21.7121 8.00852 21.6366C7.8265 21.5611 7.66129 21.4502 7.52251 21.3103L2.6897 16.4775C2.5498 16.3387 2.43889 16.1735 2.36341 15.9915C2.28792 15.8095 2.24938 15.6143 2.25001 15.4172V8.58282C2.24938 8.38577 2.28792 8.19055 2.36341 8.00852C2.43889 7.8265 2.5498 7.66129 2.6897 7.52251L7.52251 2.6897C7.66129 2.5498 7.8265 2.43889 8.00852 2.36341C8.19055 2.28792 8.38577 2.24938 8.58282 2.25001H15.4172C15.6143 2.24938 15.8095 2.28792 15.9915 2.36341C16.1735 2.43889 16.3387 2.5498 16.4775 2.6897L21.3103 7.52251C21.4502 7.66129 21.5611 7.8265 21.6366 8.00852C21.7121 8.19055 21.7506 8.38577 21.75 8.58282ZM20.25 8.58282L15.4172 3.75001H8.58282L3.75001 8.58282V15.4172L8.58282 20.25H15.4172L20.25 15.4172V8.58282ZM12 15C11.7775 15 11.56 15.066 11.375 15.1896C11.19 15.3132 11.0458 15.4889 10.9606 15.6945C10.8755 15.9001 10.8532 16.1263 10.8966 16.3445C10.94 16.5627 11.0472 16.7632 11.2045 16.9205C11.3618 17.0778 11.5623 17.185 11.7805 17.2284C11.9988 17.2718 12.225 17.2495 12.4305 17.1644C12.6361 17.0792 12.8118 16.935 12.9354 16.75C13.059 16.565 13.125 16.3475 13.125 16.125C13.125 15.8266 13.0065 15.5405 12.7955 15.3295C12.5845 15.1185 12.2984 15 12 15Z" fill="#111111"/></svg>
                        <span>*Syarat & ketenruan ! You successfully read this message...</span>
                    </div>
                </div>

                <div class="mt-24">
                    <button class="tf-btn primary">Buat Transaksi</button>
                </div>
            </form>
        </div>
    </div>
        </div>
    </div>
    @include('front.customer.partials.menubar-footer', ['active' => 'produk'])
    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
    <script>
        (function($){
            function toggleShipping(){
                var v = $('select[name="delivery_type"]').val();
                var isShip = v === 'ship';
                $('#shippingFields').toggle(isShip);
            }
            $(document).ready(function(){
                toggleShipping();
                $('select[name="delivery_type"]').on('change', toggleShipping);
            });
        })(jQuery);
    </script>
</body>
</html>