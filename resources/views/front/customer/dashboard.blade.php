<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jajan Emas - beli emas dengan mudah</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <!-- font -->
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css') }}" />
    <!-- manifest json -->
    <link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png') }}" />
    <script>
        if (localStorage.toggled === "dark-theme") {
            document.documentElement.classList.add('dark-theme');
        }
    </script>

</head>

<body>
    <div class="preload preload-container">
        <div class="logo-img">
            <img src="{{ asset('front/images/logo/logo-dark2.png') }}" alt="">
        </div>
        <div class="spinner-circle lg success">
            <span class="spinner-circle1 spinner-child"></span>
            <span class="spinner-circle2 spinner-child"></span>
            <span class="spinner-circle3 spinner-child"></span>
            <span class="spinner-circle4 spinner-child"></span>
            <span class="spinner-circle5 spinner-child"></span>
            <span class="spinner-circle6 spinner-child"></span>
            <span class="spinner-circle7 spinner-child"></span>
            <span class="spinner-circle8 spinner-child"></span>
            <span class="spinner-circle9 spinner-child"></span>
        </div>
    </div>
    <!-- /preload -->
    <div class="header-avt fixed-top">
        <a href="setup-profile" class="box-avt">
            <div class="content">
                <span class="body-4 text-dark-4">Welcome back!</span>
                <h4 class="name">
                    {{ $customer ? $customer->full_name : (auth()->user()->name ?? '') }}
                </h4>
            </div>
        </a>

        <div class="right">
             <a class="box-icon" href="#" onclick="toggleDashCartDrawer(); return false;" style="position:relative;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 2h1.74c1.08 0 1.93.93 1.84 2l-.83 9.96a2.145 2.145 0 0 0 2.14 2.36h10.89c1.04 0 1.96-.76 2.1-1.79l.8-5.56c.14-1.15-.75-2.14-1.91-2.14H5.82" stroke="#1A1528" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16.25 22a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5ZM8.25 22a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Z" stroke="#1A1528" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="dash-cart-badge" id="dashCartBadge" style="display:none;"></span>
            </a>
                <a href="#" class="box-icon logout-btn" onclick="event.preventDefault(); if (window.confirm('Anda yakin ingin logout?')) { document.getElementById('logoutForm').submit(); }" title="Logout">
                    <svg width="24" height="24" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 3h5a1 1 0 011 1v9a1 1 0 01-1 1H6" stroke="#1A1528" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 8.5h6M5 6l-2.5 2.5L5 11" stroke="#1A1528" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <form id="logoutForm" action="{{ route('customer.logout') }}" method="POST" style="display:none;">

                </form>
        </div>
    </div>
    <div class="app-content style-2">
        <div class="tf-container">
             <div class="brankas-card" style="display:flex;border-radius:16px;overflow:hidden;background:#0d1f36;height:240px;">
                <div style="flex:1;background:#0b1a2d;color:#fff;padding:16px;display:flex;flex-direction:column;justify-content:space-between;">
                    <div>
                        <div style="margin-top:6px;font-size:14px;">{{ $customer->phone_wa ?? (auth()->user()->email ?? '') }}</div>
                        <div style="font-size:14px;">{{ $customer?->full_name ?? (auth()->user()->name ?? '') }}</div>

                        <div style="display:flex;gap:32px;margin-top:12px;">
                            <div>
                                <div style="opacity:.8;font-size:12px;">PO Emas</div>
                                <div style="font-size:14px;">{{ number_format((float)$poGramTotal, 3, ',', '.') }} gr</div>
                            </div>
                            <div>
                                <div style="opacity:.8;font-size:12px;">Emas Ready</div>
                                <div style="font-size:14px;">{{ number_format((float)$readyGramTotal, 3, ',', '.') }} gr</div>
                            </div>
                        </div>
                        <div style="margin-top:12px;">
                            <div style="opacity:.8;font-size:12px;">Cicilan Emas</div>
                            <div style="font-size:14px;">{{ number_format((float)$cicilanGramTotal, 3, ',', '.') }} gr</div>
                        </div>
                    </div>
                    <div style="margin-top:8px;border-top:1px solid rgba(255,255,255,.4);padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                        <div style="font-size:16px;font-weight:600;">Saldo {{ number_format((float)$poGramTotal, 3, ',', '.') }} gr</div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7z" stroke="white" stroke-width="1.5" />
                            <circle cx="12" cy="12" r="3" stroke="white" stroke-width="1.5" />
                        </svg>
                    </div>
                </div>
                <div style="width:40%;background:linear-gradient(135deg,#c89b3c,#f2d47a);position:relative;">
                    <div style="position:absolute;top:24px;left:-40px;width:220px;height:220px;border-radius:50%;background:rgba(0,0,0,.35);"></div>
                    <div style="position:absolute;top:40px;right:24px;width:120px;height:120px;border-radius:16px;background:rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;">
                        <div style="width:88px;height:88px;border-radius:50%;border:6px solid #000;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#ffffffad;">
                            <img src="{{ asset('front/images/logo/logo-1.png') }}" alt="Logo" style="width:45px;height:45px;object-fit:cover;">
                        </div>
                    </div>
                    <div style="position:absolute;bottom:12px;right:12px;color:#000;font-weight:600;font-size:12px;display:flex;gap:12px;align-items:center;">
                        <span>JAJAN EMAS</span>
                        <span>ANTAM</span>
                    </div>
                </div>
            </div>
            <div class="mt-24">
                <h4>Menu</h4>
                <div dir="ltr" class="mt-20 swiper tf-sw" data-preview="4" data-space="20">
                    <div class="swiper-wrapper">
                        @foreach(($menus ?? []) as $m)
                            <div class="swiper-slide">
                                <div class="category-job">
                                    <a href="{{ $m->path_url ?? '#' }}" class="box-img">
                                        @php $img = $m->image; @endphp
                                        <img src="{{ empty($img) ? asset('front/images/category/company.png') : (Str::startsWith($img, ['http://','https://','/']) ? $img : asset(ltrim($img, '/'))) }}" alt="img">
                                    </a>
                                    <a class="title" href="{{ $m->path_url ?? '#' }}">{{ $m->label }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- <input type="text" id="datepicker1" value="18 Februray, 2001" placeholder="Select date of birth" class="ip-datepicker form-control line-dark hasDatepicker"> -->
            <div class="mt-24">
                <div class="fl-title d-flex justify-content-between align-items-center">
                    <h4>All Product</h4>
                    <a href="{{ route('customer.product-dan-layanan') }}" class="button-1">See All</a>
                </div>
                <div class="mt-24 mb-50">
            <div class="list-app">
                @php $produkFiltered = ($produk ?? collect())->filter(function ($p) { return ($p->is_allow_po ?? false) || ($p->is_allow_ready ?? false); }); $produkCount = $produkFiltered->count(); @endphp
                @if($produkCount === 0)
                    <div class="text-center text-muted py-3">Produk belum tersedia.</div>
                @else
                    @foreach($produkFiltered as $p)
                        <div class="box-app">
                            <div class="info-box">
                                <a href="{{ ($p->is_allow_po ? route('customer.po.create', ['pid' => encrypt((string)$p->id)]) : ($p->is_allow_ready ? route('customer.ready.index') : '#')) }}" class="logo">
                                    @php $img = $p->image_produk; @endphp
                                    <img src="{{ empty($img) ? asset('front/images/golds/antam_1.jpg') : (Str::startsWith($img, ['http://','https://','/']) ? $img : asset(ltrim($img, '/'))) }}" alt="logo">
                                </a>
                                <div class="content">
                                    <div class="h7 text-dark">
                                        <a href="{{ ($p->is_allow_po ? route('customer.po.create', ['pid' => encrypt((string)$p->id)]) : ($p->is_allow_ready ? route('customer.ready.index') : '#')) }}">
                                            {{ $p->gramasi?->nama ?? 'Produk' }} {{ number_format((float) ($p->gramasi?->gramasi ?? 0), 3) }} gr <span style="font-family: monospace !important;"> (Harga : Rp {{ number_format((float) (($p->harga_hariini ?? 0) + ($p->harga_jasa ?? 0)), 0) }})</span>
                                        </a>
                                       
                                    </div>
                                    <div class="box-map-date">
                                        <div class="d-flex gap-4 align-items-center">
                                            <i class="icon icon-wallet-2 text-primary"></i>
                                            <span class="body-3 text-dark-4">Harga: Rp {{ number_format((float) ($p->harga_hariini ?? 0), 0) }}</span>
                                        </div>
                                        <div class="d-flex gap-4 align-items-center">
                                            <i class="icon icon-wallet-2 text-primary"></i>
                                            <span class="body-3 text-dark-4">Jasa: Rp {{ number_format((float) ($p->harga_jasa ?? 0), 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                @php $hasPo = (bool)($p->is_allow_po ?? false); $hasReady = (bool)($p->is_allow_ready ?? false); $colClass = ($hasPo && $hasReady) ? 'col-6' : 'col-12'; @endphp
                                <div class="row g-2">
                                    @if($hasPo)
                                        <div class="{{ $colClass }}">
                                            <a href="{{ route('customer.po.create', ['pid' => encrypt((string)$p->id)]) }}" class="btn-app button-1 w-100">Jastip Emas</a>
                                        </div>
                                    @endif
                                    @if($hasReady)
                                        <div class="{{ $colClass }}">
                                            <a href="{{ route('customer.ready.index') }}" class="btn-app button-1 view-app w-100">Emas Ready</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
             </div> 
            </div>
        </div>
    </div>
@include('front.customer.partials.menubar-footer', ['active' => 'dashboard'])
    <!-- notification  -->
    <div class="offcanvas offcanvas-end full" id="notification">
        <div class="header fixed-top">
            <div class="left">
                <a href="javascript:void(0);" data-bs-dismiss="offcanvas" class="icon">
                    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <h3>Notification</h3>
        </div>
        <div class="overflow-auto app-content style-4">
            <div class="tf-container">
                <div class="list-noti">
                    <div class="box-noti">
                        <a href="job-detail" class="box-icon">
                            <img src="{{ asset('front/images/icon/google.png') }}" alt="">
                        </a>
                        <div class="content">
                            <div class="title">
                                <div class="h7 text-dark"><a href="job-detail">Product Design</a> <span
                                        class="dot"></span><span class="body-6 text-dark-4">Google LLC</span></div>
                            </div>
                            <p class="desc body-4 text-dark-4">Congratulations, your application on Google has been
                                accepted <span class="dot away"></span><span class="body-6 text-dark-4">5 mins
                                    ago</span></p>
                        </div>
                        <div class="more">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.99984 3.25C10.5023 3.25 10.9165 3.66421 10.9165 4.16667C10.9165 4.66912 10.5023 5.08333 9.99984 5.08333C9.49738 5.08333 9.08317 4.66912 9.08317 4.16667C9.08317 3.66421 9.49738 3.25 9.99984 3.25Z"
                                    fill="#121927" stroke="#121927" stroke-width="1.5" />
                                <path
                                    d="M9.99984 14.9165C10.5023 14.9165 10.9165 15.3307 10.9165 15.8332C10.9165 16.3356 10.5023 16.7498 9.99984 16.7498C9.49738 16.7498 9.08317 16.3356 9.08317 15.8332C9.08317 15.3307 9.49738 14.9165 9.99984 14.9165Z"
                                    fill="#121927" stroke="#121927" stroke-width="1.5" />
                                <path
                                    d="M9.99984 9.0835C10.5023 9.0835 10.9165 9.49771 10.9165 10.0002C10.9165 10.5026 10.5023 10.9168 9.99984 10.9168C9.49738 10.9168 9.08317 10.5026 9.08317 10.0002C9.08317 9.49771 9.49738 9.0835 9.99984 9.0835Z"
                                    fill="#121927" stroke="#121927" stroke-width="1.5" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/nouislider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/rangle-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/init.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>

    @php
        $poNeedShip = ($orders ?? collect())->first(function($o){
            return ($o->status === 'shipped') && ((($o->shipping_name ?? '') === '') || (($o->shipping_address ?? '') === '') || (($o->shipping_city ?? '') === '') || (($o->shipping_province ?? '') === '') || (($o->shipping_postal_code ?? '') === ''));
        });
        $prevShip = ($orders ?? collect())->first(function($o){
            return ($o->status === 'shipped') && (($o->shipping_name ?? '') !== '') && (($o->shipping_address ?? '') !== '') && (($o->shipping_city ?? '') !== '') && (($o->shipping_province ?? '') !== '') && (($o->shipping_postal_code ?? '') !== '');
        });
        $prevShipData = $prevShip ? [
            'shipping_name' => $prevShip->shipping_name,
            'shipping_phone' => $prevShip->shipping_phone,
            'shipping_address' => $prevShip->shipping_address,
            'shipping_city' => $prevShip->shipping_city,
            'shipping_province' => $prevShip->shipping_province,
            'shipping_postal_code' => $prevShip->shipping_postal_code,
        ] : null;
    @endphp

    @if($poNeedShip)
    <div class="modal fade" id="shippingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('customer.po.update-shipping', $poNeedShip) }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Order dikemas Lengkapi Alamat Pengiriman</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-2">
              <label class="form-label">Nama Penerima</label>
              <input type="text" name="shipping_name" class="form-control" value="{{ $poNeedShip->shipping_name ?? ($customer->full_name ?? '') }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">WhatsApp</label>
              <input type="text" name="shipping_phone" class="form-control" value="{{ $poNeedShip->shipping_phone ?? ($customer->phone_wa ?? '') }}">
            </div>
            <div class="mb-2">
              <label class="form-label">Alamat tujuan pengiriman (Harus benar)</label>
              <textarea name="shipping_address" class="form-control" rows="2" required>{{ $poNeedShip->shipping_address ?? ($customer->address_line ?? '') }}</textarea>
            </div>
            <div class="row g-2">
              <div class="col-4">
                <label class="form-label">Kota</label>
                <input type="text" name="shipping_city" class="form-control" value="{{ $poNeedShip->shipping_city ?? ($customer->kota ?? '') }}">
              </div>
              <div class="col-4">
                <label class="form-label">Provinsi</label>
                <input type="text" name="shipping_province" class="form-control" value="{{ $poNeedShip->shipping_province ?? ($customer->provinsi ?? '') }}">
              </div>
              <div class="col-4">
                <label class="form-label">Kode Pos</label>
                <input type="text" name="shipping_postal_code" class="form-control" value="{{ $poNeedShip->shipping_postal_code ?? ($customer->kode_pos ?? '') }}">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn-app button-1">Simpan</button>
            @if($prevShip)
              <button type="button" id="copyPrevAddressBtn" class="btn-app button-1">Samakan dengan alamat sebelumnya</button>
            @endif
            <a href="{{ route('customer.po.show', encrypt($poNeedShip->id)) }}" class="btn btn-link">Lihat PO</a>
          </div>
        </form>
      </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var el = document.getElementById('shippingModal');
        if (el) {
            var m = new bootstrap.Modal(el);
            m.show();
        }
        var prevShipData = @json($prevShipData);
        var copyBtn = document.getElementById('copyPrevAddressBtn');
        if (copyBtn && prevShipData) {
            copyBtn.addEventListener('click', function(){
                var root = document.getElementById('shippingModal');
                if (!root) return;
                var setVal = function(name, val){
                    var input = root.querySelector('[name="'+name+'"]');
                    if (input) input.value = val || '';
                };
                setVal('shipping_name', prevShipData.shipping_name);
                setVal('shipping_phone', prevShipData.shipping_phone);
                setVal('shipping_address', prevShipData.shipping_address);
                setVal('shipping_city', prevShipData.shipping_city);
                setVal('shipping_province', prevShipData.shipping_province);
                setVal('shipping_postal_code', prevShipData.shipping_postal_code);
            });
        }
    });
    </script>
    @endif

    <!-- Cart Drawer -->
    <style>
        .dash-cart-badge {
            position:absolute; top:-4px; right:-4px;
            background:#e74c3c; color:#fff;
            font-size:10px; font-weight:700;
            min-width:18px; height:18px; line-height:18px;
            text-align:center; border-radius:50%;
        }
        .dc-overlay {
            position:fixed; inset:0; background:rgba(0,0,0,.4);
            z-index:9998; opacity:0; visibility:hidden; transition:all .3s;
        }
        .dc-overlay.active { opacity:1; visibility:visible; }
        .dc-drawer {
            position:fixed; top:0; right:-380px;
            width:360px; max-width:90vw; height:100%;
            background:#fff; z-index:9999;
            box-shadow:-4px 0 20px rgba(0,0,0,.15);
            transition:right .3s; display:flex; flex-direction:column;
            font-family:'Poppins',sans-serif;
        }
        .dc-drawer.active { right:0; }
        .dc-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:16px 20px; border-bottom:1px solid #eee;
        }
        .dc-header h4 { margin:0; font-size:16px; font-weight:700; color:#333; }
        .dc-header h4 i { margin-right:8px; color:#dcb73f; }
        .dc-close { background:none; border:none; font-size:22px; color:#999; cursor:pointer; }
        .dc-close:hover { color:#333; }
        .dc-body { flex:1; overflow-y:auto; padding:16px 20px; }
        .dc-empty { text-align:center; color:#aaa; padding:40px 0; font-size:14px; }
        .dc-empty i { display:block; font-size:40px; margin-bottom:12px; }
        .dc-item { display:flex; gap:12px; padding:12px 0; border-bottom:1px solid #f0f0f0; }
        .dc-item-img { width:56px; height:56px; border-radius:8px; overflow:hidden; flex-shrink:0; background:#f5f5f5; }
        .dc-item-img img { width:100%; height:100%; object-fit:cover; }
        .dc-item-info { flex:1; min-width:0; }
        .dc-item-name { font-size:13px; font-weight:600; color:#333; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .dc-item-price { font-size:13px; font-weight:700; color:#dcb73f; margin-top:2px; }
        .dc-item-qty { display:flex; align-items:center; gap:8px; margin-top:6px; }
        .dc-item-qty button {
            width:24px; height:24px; border:1px solid #ddd; border-radius:4px;
            background:#fafafa; cursor:pointer; font-size:14px;
            display:flex; align-items:center; justify-content:center;
        }
        .dc-item-qty button:hover { background:#f0f0f0; }
        .dc-item-qty span { font-size:13px; font-weight:600; min-width:20px; text-align:center; }
        .dc-item-rm { background:none; border:none; color:#e74c3c; font-size:14px; cursor:pointer; align-self:flex-start; padding:0; margin-top:2px; }
        .dc-item-rm:hover { color:#c0392b; }
        .dc-footer { padding:16px 20px; border-top:1px solid #eee; }
        .dc-total { display:flex; justify-content:space-between; font-size:15px; font-weight:700; color:#333; margin-bottom:12px; }
        .dc-total span:last-child { color:#dcb73f; }
        .dc-checkout {
            display:block; width:100%; padding:12px; border:none; border-radius:25px;
            background:#dcb73f; color:#fff; font-size:14px; font-weight:700;
            cursor:pointer; text-align:center; text-transform:uppercase; letter-spacing:1px;
        }
        .dc-checkout:hover { background:#c9a636; }
        .dc-checkout:disabled { background:#ccc; cursor:not-allowed; }

        /* Shipping form step */
        .dc-step { display:none; flex-direction:column; flex:1; overflow:hidden; }
        .dc-step.active { display:flex; }
        .dc-form-group { margin-bottom:14px; }
        .dc-form-group label { display:block; font-size:12px; font-weight:600; color:#555; margin-bottom:4px; }
        .dc-form-group input, .dc-form-group textarea, .dc-form-group select {
            width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px;
            font-size:13px; font-family:inherit; outline:none; transition:border-color .2s;
        }
        .dc-form-group input:focus, .dc-form-group textarea:focus, .dc-form-group select:focus { border-color:#dcb73f; }
        .dc-form-group textarea { resize:vertical; min-height:60px; }
        .dc-back-btn {
            background:none; border:1px solid #ddd; border-radius:25px; padding:10px;
            width:100%; font-size:13px; font-weight:600; cursor:pointer; margin-bottom:10px;
            color:#555; transition:all .2s;
        }
        .dc-back-btn:hover { border-color:#dcb73f; color:#dcb73f; }
        .dc-addr-card {
            border:1px solid #eee; border-radius:10px; padding:12px; margin-bottom:10px;
            cursor:pointer; transition:all .2s; position:relative;
        }
        .dc-addr-card:hover, .dc-addr-card.selected { border-color:#dcb73f; background:#fffdf5; }
        .dc-addr-card.selected::after {
            content:'✓'; position:absolute; top:10px; right:12px;
            color:#dcb73f; font-weight:700; font-size:16px;
        }
        .dc-addr-name { font-size:13px; font-weight:600; color:#333; }
        .dc-addr-detail { font-size:12px; color:#777; margin-top:2px; }
        .dc-divider { text-align:center; color:#aaa; font-size:12px; margin:16px 0; position:relative; }
        .dc-divider::before, .dc-divider::after {
            content:''; position:absolute; top:50%; width:40%; height:1px; background:#eee;
        }
        .dc-divider::before { left:0; }
        .dc-divider::after { right:0; }
    </style>

    <div class="dc-overlay" id="dcOverlay" onclick="toggleDashCartDrawer()"></div>
    <div class="dc-drawer" id="dcDrawer">
        <div class="dc-header">
            <h4><i class="icon-shopping-cart"></i> Keranjang</h4>
            <button class="dc-close" onclick="toggleDashCartDrawer()">&times;</button>
        </div>

        <!-- Step 1: Cart Items -->
        <div class="dc-step active" id="dcStepCart">
            <div class="dc-body" id="dcBody">
                <div class="dc-empty"><i class="icon-shopping-bag"></i>Keranjang masih kosong</div>
            </div>
            <div class="dc-footer" id="dcFooter" style="display:none;">
                <div class="dc-total">
                    <span>Total</span>
                    <span id="dcTotal">Rp 0</span>
                </div>
                <button class="dc-checkout" onclick="dcShowShipping()">
                    <i class="icon-arrow-right"></i> Lanjut ke Pengiriman
                </button>
            </div>
        </div>

        <!-- Step 2: Shipping / Address -->
        <div class="dc-step" id="dcStepShipping">
            <div class="dc-body" style="padding:16px 20px;">
                <button class="dc-back-btn" onclick="dcBackToCart()">← Kembali ke Keranjang</button>

                @if(($customerAddresses ?? collect())->count())
                <p style="font-size:13px;font-weight:600;color:#333;margin-bottom:10px;">Pilih Alamat Tersimpan</p>
                @foreach($customerAddresses as $addr)
                <div class="dc-addr-card" data-addr-id="{{ $addr->id }}" onclick="dcSelectAddr(this, {{ $addr->id }})">
                    <div class="dc-addr-name">{{ $addr->name }} &middot; {{ $addr->phone }}</div>
                    <div class="dc-addr-detail">{{ is_array($addr->lines) ? implode(', ', $addr->lines) : $addr->lines }}, {{ $addr->city }}</div>
                    @if($addr->tag)<div class="dc-addr-detail" style="color:#dcb73f;">{{ $addr->tag }}</div>@endif
                </div>
                @endforeach
                <div class="dc-divider" id="dcDivider">atau isi manual</div>
                @endif

                <div id="dcNewAddrForm">
                    <div class="dc-form-group">
                        <label>Nama Penerima *</label>
                        <input type="text" id="dcShipName" value="{{ $customer->full_name ?? '' }}" placeholder="Nama lengkap penerima">
                    </div>
                    <div class="dc-form-group">
                        <label>No. Telepon *</label>
                        <input type="text" id="dcShipPhone" value="{{ $customer->phone_wa ?? '' }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="dc-form-group">
                        <label>Alamat Lengkap *</label>
                        <textarea id="dcShipAddress" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"></textarea>
                    </div>
                    <div class="dc-form-group">
                        <label>Kota *</label>
                        <input type="text" id="dcShipCity" placeholder="Kota / Kabupaten">
                    </div>
                    <div class="dc-form-group">
                        <label>Catatan (opsional)</label>
                        <textarea id="dcShipNote" placeholder="Catatan untuk penjual" style="min-height:40px;"></textarea>
                    </div>
                </div>
            </div>
            <div class="dc-footer">
                <button class="dc-checkout" id="dcCheckoutBtn" onclick="dcDoCheckout()">
                    <i class="icon-arrow-right"></i> Selesaikan Pembayaran
                </button>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var CART_KEY = 'jj_cart';
        var selectedAddrId = null;

        function getCart(){ try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch(e){ return []; } }
        function saveCart(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); }
        function fmtRp(n){ return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

        window.toggleDashCartDrawer = function(){
            var o = document.getElementById('dcOverlay'), d = document.getElementById('dcDrawer');
            if(d.classList.contains('active')){
                o.classList.remove('active'); d.classList.remove('active'); document.body.style.overflow='';
                dcBackToCart();
            } else {
                o.classList.add('active'); d.classList.add('active'); document.body.style.overflow='hidden';
            }
        };
        window.dcChangeQty = function(id, delta){
            var cart = getCart();
            for(var i=0;i<cart.length;i++){
                if(cart[i].id===id){ cart[i].qty+=delta; if(cart[i].qty<=0) cart.splice(i,1); else if(cart[i].qty>cart[i].maxStok) cart[i].qty=cart[i].maxStok; break; }
            }
            saveCart(cart); dcRender();
        };
        window.dcRemove = function(id){
            saveCart(getCart().filter(function(i){ return i.id!==id; })); dcRender();
        };

        window.dcShowShipping = function(){
            document.getElementById('dcStepCart').classList.remove('active');
            document.getElementById('dcStepShipping').classList.add('active');
        };
        window.dcBackToCart = function(){
            document.getElementById('dcStepShipping').classList.remove('active');
            document.getElementById('dcStepCart').classList.add('active');
        };
        window.dcSelectAddr = function(el, id){
            var cards = document.querySelectorAll('.dc-addr-card');
            for(var i=0;i<cards.length;i++) cards[i].classList.remove('selected');
            el.classList.add('selected');
            selectedAddrId = id;
            // Hide manual form
            var form = document.getElementById('dcNewAddrForm');
            var divider = document.getElementById('dcDivider');
            if(form) form.style.display = 'none';
            if(divider) divider.innerHTML = '<a href="#" onclick="dcShowManualForm(); return false;" style="color:#dcb73f;text-decoration:none;font-weight:600;">Gunakan alamat baru</a>';
        };
        window.dcShowManualForm = function(){
            selectedAddrId = null;
            var cards = document.querySelectorAll('.dc-addr-card');
            for(var i=0;i<cards.length;i++) cards[i].classList.remove('selected');
            var form = document.getElementById('dcNewAddrForm');
            var divider = document.getElementById('dcDivider');
            if(form) form.style.display = 'block';
            if(divider) divider.innerHTML = 'atau isi manual';
        };

        function dcRender(){
            var cart=getCart(), body=document.getElementById('dcBody'), footer=document.getElementById('dcFooter'),
                badge=document.getElementById('dashCartBadge'), tc=0, tp=0;
            for(var i=0;i<cart.length;i++){ tc+=cart[i].qty; tp+=cart[i].qty*cart[i].price; }
            if(tc>0){ badge.textContent=tc>99?'99+':tc; badge.style.display='inline-block'; }
            else { badge.style.display='none'; }
            if(!cart.length){ body.innerHTML='<div class="dc-empty"><i class="icon-shopping-bag" style="font-size:40px;display:block;margin-bottom:12px;"></i>Keranjang masih kosong</div>'; footer.style.display='none'; return; }
            var h='';
            for(var i=0;i<cart.length;i++){
                var c=cart[i];
                h+='<div class="dc-item"><div class="dc-item-img"><img src="'+c.image+'" alt="'+c.name+'"></div>'
                  +'<div class="dc-item-info"><div class="dc-item-name">'+c.name+'</div><div class="dc-item-price">'+fmtRp(c.price)+'</div>'
                  +'<div class="dc-item-qty"><button onclick="dcChangeQty('+c.id+',-1)">&minus;</button><span>'+c.qty+'</span><button onclick="dcChangeQty('+c.id+',1)">+</button></div></div>'
                  +'<button class="dc-item-rm" onclick="dcRemove('+c.id+')"><i class="icon-trash-2"></i></button></div>';
            }
            body.innerHTML=h; footer.style.display='block';
            document.getElementById('dcTotal').textContent=fmtRp(tp);
        }
        dcRender();

        window.dcDoCheckout = function(){
            var cart = getCart();
            if(!cart.length){ alert('Keranjang kosong.'); return; }

            // Build payload
            var payload = { items: [] };
            for(var i=0;i<cart.length;i++){
                payload.items.push({ id: cart[i].id, qty: cart[i].qty });
            }

            if(selectedAddrId){
                payload.address_id = selectedAddrId;
            } else {
                var nm = document.getElementById('dcShipName').value.trim();
                var ph = document.getElementById('dcShipPhone').value.trim();
                var ad = document.getElementById('dcShipAddress').value.trim();
                var ct = document.getElementById('dcShipCity').value.trim();
                if(!nm || !ph || !ad || !ct){
                    alert('Silakan pilih alamat tersimpan atau isi semua field alamat pengiriman.');
                    return;
                }
                payload.shipping_name = nm;
                payload.shipping_phone = ph;
                payload.shipping_address = ad;
                payload.shipping_city = ct;
            }
            payload.catatan = (document.getElementById('dcShipNote').value || '').trim();

            var btn = document.getElementById('dcCheckoutBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="icon-loader"></i> Memproses...';

            fetch('{{ route("customer.ready.cart-checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(function(r){ return r.json().then(function(d){ return { ok: r.ok, data: d }; }); })
            .then(function(res){
                if(res.ok && res.data.status){
                    localStorage.removeItem(CART_KEY);
                    window.location.href = res.data.data.redirect_url;
                } else {
                    var msg = res.data.message || res.data.error || 'Checkout gagal. Silakan coba lagi.';
                    alert(msg);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="icon-arrow-right"></i> Selesaikan Pembayaran';
                }
            })
            .catch(function(err){
                alert('Terjadi kesalahan: ' + err.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="icon-arrow-right"></i> Selesaikan Pembayaran';
            });
        };
    })();
    </script>

</body>

</html>