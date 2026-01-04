<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <!-- font -->
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css')}}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css')}}" />

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png')}}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/images/logo/168.png')}}" />
    <title>Titip Emas || Jajan Emas</title>
    <!-- Apply dark theme early to avoid white flash -->
    <script>
        if (localStorage.toggled === "dark-theme") {
            document.documentElement.classList.add('dark-theme');
        }
    </script>
    <style>
        @keyframes bounce-vertical {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .shipping-alert-bubble {
            animation: bounce-vertical 1s infinite;
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            position: absolute;
            top: -45px;
            right: 0;
            z-index: 10;
            display: none;
        }
        .shipping-alert-bubble::after {
            content: '';
            position: absolute;
            bottom: -6px;
            right: 20px;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #dc3545;
        }
    </style>
</head>

<body>
    <!-- preloade -->
    <div class="preload preload-container">
        <div class="logo-img">
            <img src="{{ asset('front/images/logo/logo-dark2.png')}}" alt="">
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
     
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.dashboard') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
        <h3>Pre Order Emas</h3>
    </div>
    <div class="app-content style-3">
        <div class="tf-container">
            <form action="{{ route('customer.po.store') }}" method="POST" class="mt-10">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-info light alert-dismissible fade show mb-3" role="alert">
                        <strong>Terjadi kesalahan.</strong>
                        <ul class="mb-0 mt-8">
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-field form-2">
                    <div class="label h7">Pilih Gramasi Emas</div>
                    <fieldset class="mt-12">
                         <select class="form-control @error('id_master_produk_dan_layanan') is-invalid @enderror" name="id_master_produk_dan_layanan" id="produkId">
                            @php
                                $oldEnc = old('id_master_produk_dan_layanan');
                                $pidEnc = request()->query('pid');
                                $selectedId = null;
                                if ($oldEnc) {
                                    try { $selectedId = decrypt($oldEnc); } catch (\Throwable $e) { $selectedId = null; }
                                } elseif ($pidEnc) {
                                    try { $selectedId = decrypt($pidEnc); } catch (\Throwable $e) { $selectedId = null; }
                                }
                            @endphp
                            @foreach($produkPo as $p)
                                <option value="{{ encrypt((string)$p->id) }}" data-harga="{{ (float)($p->harga_hariini ?? 0) }}" data-jasa="{{ (float)($p->harga_jasa ?? 0) }}" data-gram="{{ (float)optional($p->gramasi)->gramasi }}" {{ (string)$selectedId === (string)$p->id ? 'selected' : '' }}>
                                    {{ optional($p->gramasi)->nama }} - {{ number_format((float)optional($p->gramasi)->gramasi, 3, ',', '.') }} gram
                                </option>
                            @endforeach
                            @error('id_master_produk_dan_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </select>
                    </fieldset>
                </div>
                
                <div class="form-field form-2 mt-24">
                    <div class="label h7">Banyak kepingan</div>
                    <fieldset class="mt-12">
                       <input type="hidden" name="gramasi_emas" id="totalGram" value="{{ old('gramasi_emas', 0) }}">
                       <select class="form-select" id="qty" name="qty">
                            <option value="1" @selected(old('qty','1')=='1')>1 pcs</option>
                            <option value="2" @selected(old('qty','1')=='2')>2 pcs </option>
                            <option value="3" @selected(old('qty','1')=='3')>3 pcs</option>
                            <option value="4" @selected(old('qty','1')=='4')>4 pcs</option>
                            <option value="5" @selected(old('qty','1')=='5')>5 pcs</option>
                            <option value="6" @selected(old('qty','1')=='6')>6 pcs</option>
                            <option value="7" @selected(old('qty','1')=='7')>7 pcs</option>
                            <option value="8" @selected(old('qty','1')=='8')>8 pcs</option>
                            <option value="9" @selected(old('qty','1')=='9')>9 pcs</option>
                            <option value="10" @selected(old('qty','1')=='10')>10 pcs</option>
                            <option value="11" @selected(old('qty','1')=='11')>11 pcs</option>
                            <option value="12" @selected(old('qty','1')=='12')>12 pcs</option>
                            <option value="13" @selected(old('qty','1')=='13')>13 pcs</option>
                            <option value="14" @selected(old('qty','1')=='14')>14 pcs</option>
                            <option value="15" @selected(old('qty','1')=='15')>15 pcs</option>
                            <option value="16" @selected(old('qty','1')=='16')>16 pcs</option>
                            <option value="17" @selected(old('qty','1')=='17')>17 pcs</option>
                            <option value="18" @selected(old('qty','1')=='18')>18 pcs</option>
                            <option value="19" @selected(old('qty','1')=='19')>19 pcs</option>
                            <option value="20" @selected(old('qty','1')=='20')>20 pcs</option>
                        </select>
                        @error('gramasi_emas')<div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>
                <div class="form-field form-2 mt-24">
                    <div class="label h7">Metode Pengiriman</div>
                    <fieldset class="mt-12 d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_type" id="delivery_ship" value="ship" checked>
                            <label class="form-check-label" for="delivery_ship">
                                Pakai Ekspedisi
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_type" id="delivery_pickup" value="pickup">
                            <label class="form-check-label" for="delivery_pickup">
                                Ambil Sendiri
                            </label>
                        </div>
                    </fieldset>
                </div>

                <div id="shipping-section" class="form-field form-2 mt-24 position-relative">
                    <div class="label h7">Kota Tujuan Pengiriman</div>
                    <fieldset class="mt-12 d-flex gap-2">
                        <div class="position-relative w-100">
                            <input type="text" id="shipping_city_input" name="shipping_city" class="form-control" placeholder="Masukan Kota Tujuan (min. 4 huruf)" value="{{ old('shipping_city') }}" autocomplete="off">
                            <input type="hidden" name="shipping_city_code" id="shipping_city_code" value="{{ old('shipping_city_code') }}">
                            <div id="city-results" class="list-group position-absolute w-100 start-0" style="display:none; z-index: 1000; max-height: 250px; overflow-y: auto; top: 100%; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                        </div>
                        <div class="position-relative" style="flex-shrink: 0;">
                            <div id="shipping-indicator" class="shipping-alert-bubble">
                                👇 Klik "Hitung" di sini!
                            </div>
                            <button type="button" id="btnCheckOngkir" class="tf-btn primary" style="width: auto; padding: 0 20px; height: 100%;">Hitung</button>
                        </div>
                    </fieldset>
                    <div id="jne-result" class="mt-20" style="display:none;">
                        <div class="label h7 mb-12">Pilih Layanan Pengiriman</div>
                        <select id="shipping_service" name="shipping_service" class="form-select">
                        </select>
                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                    </div>
                </div>
                <div class="card-body mt-20">
                    <div class="alert alert-primary light alert-dismissible fade show mb-10" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3.27 15.084c.693-1.457 1.04-2.186 1.644-2.607q.074-.051.15-.098C5.691 12 6.461 12 8 12s2.308 0 2.937.38q.075.046.149.096c.604.422.951 1.15 1.645 2.608c1.034 2.173 1.55 3.26 1.112 4.058l-.052.089C13.308 20 12.161 20 9.87 20H6.13c-2.292 0-3.439 0-3.922-.77l-.052-.088c-.439-.798.078-1.885 1.112-4.058Z"/><path stroke-linecap="round" d="M14.547 12.02c.396-.02.869-.02 1.452-.02c1.538 0 2.308 0 2.937.38q.075.046.149.096c.604.422.951 1.15 1.645 2.608c1.034 2.173 1.55 3.26 1.112 4.058q-.024.045-.052.089c-.483.769-1.63.769-3.922.769h-1.129m.868-11a41 41 0 0 0-.876-1.916c-.694-1.457-1.04-2.186-1.645-2.607a3 3 0 0 0-.15-.098C14.309 4 13.54 4 12 4s-2.308 0-2.937.38q-.075.045-.15.096c-.603.422-.95 1.15-1.644 2.608c-.361.76-.66 1.388-.876 1.916"/></g></svg>
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center font-monospace">
                                <span class="fw-semibold">Estimasi Bayar</span>
                                <span id="estimasiTotal" class="fw-bold text-success fs-3 lh-1">Rp 0</span>
                            </div>
                            <div class="small text-muted fw-semibold">
                                Per item: <span id="estimasiHarga">Rp 0</span> × <span id="estimasiQty">1</span> pcs<br>
                                <span id="ongkirInfo" style="display:none;">+ Ongkir: <span id="ongkirVal">Rp 0</span></span>
                            </div>
                        </div>
                    </div>
                </div>      
                <div class="card-body mt-20">
                    <div class="alert alert-light alert-dismissible fade show" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M11.25 12.75V7.50001C11.25 7.3011 11.329 7.11033 11.4697 6.96968C11.6103 6.82903 11.8011 6.75001 12 6.75001C12.1989 6.75001 12.3897 6.82903 12.5303 6.96968C12.671 7.11033 12.75 7.3011 12.75 7.50001V12.75C12.75 12.9489 12.671 13.1397 12.5303 13.2803C12.3897 13.421 12.1989 13.5 12 13.5C11.8011 13.5 11.6103 13.421 11.4697 13.2803C11.329 13.1397 11.25 12.9489 11.25 12.75ZM21.75 8.58282V15.4172C21.7506 15.6143 21.7121 15.8095 21.6366 15.9915C21.5611 16.1735 21.4502 16.3387 21.3103 16.4775L16.4775 21.3103C16.3387 21.4502 16.1735 21.5611 15.9915 21.6366C15.8095 21.7121 15.6143 21.7506 15.4172 21.75H8.58282C8.38577 21.7506 8.19055 21.7121 8.00852 21.6366C7.8265 21.5611 7.66129 21.4502 7.52251 21.3103L2.6897 16.4775C2.5498 16.3387 2.43889 16.1735 2.36341 15.9915C2.28792 15.8095 2.24938 15.6143 2.25001 15.4172V8.58282C2.24938 8.38577 2.28792 8.19055 2.36341 8.00852C2.43889 7.8265 2.5498 7.66129 2.6897 7.52251L7.52251 2.6897C7.66129 2.5498 7.8265 2.43889 8.00852 2.36341C8.19055 2.28792 8.38577 2.24938 8.58282 2.25001H15.4172C15.6143 2.24938 15.8095 2.28792 15.9915 2.36341C16.1735 2.43889 16.3387 2.5498 16.4775 2.6897L21.3103 7.52251C21.4502 7.66129 21.5611 7.8265 21.6366 8.00852C21.7121 8.19055 21.7506 8.38577 21.75 8.58282ZM20.25 8.58282L15.4172 3.75001H8.58282L3.75001 8.58282V15.4172L8.58282 20.25H15.4172L20.25 15.4172V8.58282ZM12 15C11.7775 15 11.56 15.066 11.375 15.1896C11.19 15.3132 11.0458 15.4889 10.9606 15.6945C10.8755 15.9001 10.8532 16.1263 10.8966 16.3445C10.94 16.5627 11.0472 16.7632 11.2045 16.9205C11.3618 17.0778 11.5623 17.185 11.7805 17.2284C11.9988 17.2718 12.225 17.2495 12.4305 17.1644C12.6361 17.0792 12.8118 16.935 12.9354 16.75C13.059 16.565 13.125 16.3475 13.125 16.125C13.125 15.8266 13.0065 15.5405 12.7955 15.3295C12.5845 15.1185 12.2984 15 12 15Z"
                                fill="#111111" />
                        </svg>
                        <span>*Syarat & ketenruan ! You successfully read this message...</span>
                    </div>
                </div>        
                <div class="mt-24">
                    <button id="btnSubmit" class="tf-btn primary">Lanjutkan</button>
                </div>
            </form>

        </div>

    </div>
    @include('front.customer.partials.menubar-footer', ['active' => 'pre-order-emas'])
    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
    <script>
        (function($){
            function formatIDR(n){ return 'Rp ' + (Number(n)||0).toLocaleString('id-ID', { maximumFractionDigits: 0 }); }
            function updateEstimasi(){
                var $prod = $('select[name="id_master_produk_dan_layanan"]');
                var opt = $prod.find('option:selected');
                var harga = Number(opt.data('harga')) || 0;
                var jasa = Number(opt.data('jasa')) || 0;
                var gram = Number(opt.data('gram')) || 0;
                var qty = Number($('#qty').val()) || 1;
                var ongkir = Number($('#shipping_cost').val()) || 0;

                var perItem = harga + jasa;
                var total = (perItem * qty) + ongkir;

                $('#estimasiHarga').text(formatIDR(perItem));
                $('#estimasiQty').text(qty);
                $('#estimasiTotal').text(formatIDR(total));
                $('#totalGram').val((gram * qty).toFixed(3));
                
                if(ongkir > 0) {
                    $('#ongkirVal').text(formatIDR(ongkir));
                    $('#ongkirInfo').show();
                } else {
                    $('#ongkirInfo').hide();
                }
            }
            $(document).ready(function(){
                $('select[name="id_master_produk_dan_layanan"], #qty').on('change', updateEstimasi);
                updateEstimasi();

                // Delivery Type Toggle
                $('input[name="delivery_type"]').on('change', function() {
                    var type = $('input[name="delivery_type"]:checked').val();
                    if (type === 'pickup') {
                        $('#shipping-section').hide();
                        $('#shipping_cost').val(0);
                        updateEstimasi();
                    } else {
                        $('#shipping-section').show();
                        var cost = $('#shipping_cost').val();
                        updateEstimasi();
                    }
                });

                // JNE City Search
                var searchTimer;
                $('#shipping_city_input').on('input', function() {
                    var query = $(this).val();
                    var $results = $('#city-results');
                    
                    clearTimeout(searchTimer);
                    
                    if(query.length >= 4) {
                        searchTimer = setTimeout(function() {
                            $.ajax({
                                url: '{{ route("customer.api.jne.cities") }}',
                                data: { search: query },
                                method: 'GET',
                                success: function(response) {
                                    if(response.status && response.data && response.data.length > 0) {
                                        var html = '';
                                        $.each(response.data, function(i, item){
                                            html += '<a href="#" class="list-group-item list-group-item-action city-item" data-code="'+item.code+'" data-label="'+item.label+'">'+item.label+'</a>';
                                        });
                                        $results.html(html).show();
                                    } else {
                                        $results.hide();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching cities:', error);
                                    // Fallback or silence? Keep silent for UI, user can still type manually if needed
                                    $results.hide();
                                }
                            });
                        }, 500);
                    } else {
                        $results.hide();
                    }
                });

                $(document).on('click', '.city-item', function(e) {
                    e.preventDefault();
                    var label = $(this).data('label');
                    var code = $(this).data('code');
                    $('#shipping_city_input').val(label);
                    $('#shipping_city_code').val(code);
                    $('#city-results').hide();
                });

                // Hide dropdown when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.form-field').length) {
                        $('#city-results').hide();
                    }
                });

                // Button Hitung Ongkir Handler
                $('#btnCheckOngkir').on('click', function() {
                    $('#shipping-indicator').fadeOut(); // Hide indicator
                    var code = $('#shipping_city_code').val();
                    if(!code) {
                        alert('Silakan pilih kota tujuan terlebih dahulu dari dropdown pencarian.');
                        return;
                    }
                    
                    var $btn = $(this);
                    var originalText = $btn.text();
                    $btn.prop('disabled', true).text('Loading...');

                    $.ajax({
                        url: '{{ route("customer.api.jne.shipping-fee") }}',
                        method: 'GET',
                        data: { destination: code },
                        success: function(response) {
                            if(response.status && response.data && response.data.length > 0) {
                                var $select = $('#shipping_service');
                                $select.empty().append('');
                                
                                var hasReg = false;
                                $.each(response.data, function(i, item){
                                    if(item.service === 'REG') hasReg = true;
                                    $select.append($('<option>', {
                                        value: item.price,
                                        text: item.label,
                                        'data-service': item.service,
                                        'data-etd': item.etd
                                    }));
                                });
                                
                                if(hasReg) {
                                    $select.find('option').each(function(){
                                        var isReg = $(this).data('service') === 'REG';
                                        $(this).prop('disabled', !isReg);
                                        if(isReg) $(this).prop('selected', true);
                                    });
                                } else {
                                    $select.find('option').prop('disabled', false).first().prop('selected', true);
                                }
                                
                                $select.trigger('change');
                                $('#jne-result').show();
                            } else {
                                alert('Tidak ada layanan pengiriman yang tersedia.');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching shipping fee:', xhr);
                            alert('Gagal mengambil ongkir. Coba lagi nanti.');
                        },
                        complete: function() {
                            $btn.prop('disabled', false).text(originalText);
                        }
                    });
                });

                $('#shipping_service').on('change', function() {
                    var cost = $(this).val();
                    $('#shipping_cost').val(cost);
                    updateEstimasi();
                });

                // Submit Button Handler
                $('#btnSubmit').on('click', function(e) {
                    var type = $('input[name="delivery_type"]:checked').val();
                    if(type === 'ship') {
                        var cost = $('#shipping_cost').val();
                        if(!cost || parseFloat(cost) <= 0) {
                            e.preventDefault();
                            
                            // Show indicator with animation
                            $('#shipping-indicator').fadeIn();
                            $('html, body').animate({
                                scrollTop: $("#shipping-section").offset().top - 100
                            }, 500);

                            alert('Mohon klik tombol "Hitung" untuk melihat ongkos kirim terlebih dahulu.');
                            return false;
                        }
                    }
                });
            });
        })(jQuery);
    </script>

</body>

</html>