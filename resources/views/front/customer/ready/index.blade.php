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
    <title>Emas Ready || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
    <style>
        /* ===== Product Card Override ===== */
        .box-app .box-btn {
            display:flex !important;
            gap:8px !important;
        }
        .box-app .box-btn > * {
            flex:1; min-width:0;
            display:flex; align-items:center; justify-content:center;
            padding:8px 6px; border-radius:10px;
            font-size:12px; font-weight:600; text-align:center;
            text-decoration:none; white-space:nowrap;
        }
        .box-app { margin-bottom:16px; }
        .btn-cart {
            border:none; background:#dcb73f; color:#fff;
            cursor:pointer; transition:background .2s; gap:4px;
        }
        .btn-cart:hover { background:#c9a636; }
        .btn-cart i { font-size:13px; }

        /* Cart badge on header */
        .header-cart { position:relative; margin-right:4px; }
        .header-cart i { font-size:20px; color:#333; }
        .header-cart-badge {
            position:absolute; top:-6px; right:-8px;
            background:#e74c3c; color:#fff; font-size:9px; font-weight:700;
            min-width:16px; height:16px; line-height:16px;
            text-align:center; border-radius:50%; display:none;
        }
        .header-cart-badge.has-items { display:inline-block; }

        /* Cart Drawer */
        .rc-overlay { position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:9998; opacity:0; visibility:hidden; transition:all .3s; }
        .rc-overlay.active { opacity:1; visibility:visible; }
        .rc-drawer {
            position:fixed; top:0; right:-380px; width:360px; max-width:90vw; height:100%;
            background:#fff; z-index:9999; box-shadow:-4px 0 20px rgba(0,0,0,.15);
            transition:right .3s; display:flex; flex-direction:column; font-family:inherit;
        }
        .rc-drawer.active { right:0; }
        .rc-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #eee; }
        .rc-header h4 { margin:0; font-size:16px; font-weight:700; color:#333; }
        .rc-close { background:none; border:none; font-size:22px; color:#999; cursor:pointer; }
        .rc-close:hover { color:#333; }
        .rc-body { flex:1; overflow-y:auto; padding:16px 20px; }
        .rc-empty { text-align:center; color:#aaa; padding:40px 0; font-size:14px; }
        .rc-item { display:flex; gap:12px; padding:12px 0; border-bottom:1px solid #f0f0f0; }
        .rc-item:last-child { border-bottom:none; }
        .rc-item-img { width:50px; height:50px; border-radius:8px; overflow:hidden; flex-shrink:0; background:#f5f5f5; }
        .rc-item-img img { width:100%; height:100%; object-fit:cover; }
        .rc-item-info { flex:1; min-width:0; }
        .rc-item-name { font-size:13px; font-weight:600; color:#333; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .rc-item-price { font-size:13px; font-weight:700; color:#dcb73f; margin-top:2px; }
        .rc-item-qty { display:flex; align-items:center; gap:8px; margin-top:6px; }
        .rc-item-qty button { width:24px; height:24px; border:1px solid #ddd; border-radius:4px; background:#fafafa; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; }
        .rc-item-qty button:hover { background:#f0f0f0; }
        .rc-item-qty span { font-size:13px; font-weight:600; min-width:20px; text-align:center; }
        .rc-item-rm { background:none; border:none; color:#e74c3c; font-size:14px; cursor:pointer; align-self:flex-start; padding:0; margin-top:2px; }
        .rc-footer { padding:16px 20px; border-top:1px solid #eee; }
        .rc-total { display:flex; justify-content:space-between; font-size:15px; font-weight:700; color:#333; margin-bottom:12px; }
        .rc-total span:last-child { color:#dcb73f; }
        .rc-checkout-btn {
            display:block; width:100%; padding:12px; border:none; border-radius:25px;
            background:#dcb73f; color:#fff; font-size:14px; font-weight:700;
            cursor:pointer; text-align:center; text-transform:uppercase; letter-spacing:1px;
        }
        .rc-checkout-btn:hover { background:#c9a636; }
    </style>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.dashboard') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Emas Ready</h3>
        <div class="right" style="display:flex;align-items:center;">
            <a href="#" class="header-cart" onclick="toggleReadyCart(); return false;">
                <i class="icon-shopping-cart"></i>
                <span class="header-cart-badge" id="rcBadge">0</span>
            </a>
        </div>
    </div>
    <div class="app-content style-3">
        <div class="tf-container">

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="p-2">
                @forelse ($stocks as $s)
                    <div class="box-app">
                        <div class="info-box">
                            <a href="{{ route('customer.ready.stock', ['stock' => encrypt((string)$s->id)]) }}" class="logo">
                                <img src="{{ ($s->images && is_array($s->images) && count($s->images) > 0) ? (Str::startsWith($s->images[0], ['http://','https://']) ? $s->images[0] : asset($s->images[0])) : asset('front/images/golds/antam_2.jpg') }}" alt="logo">
                            </a>
                            <div class="content">
                                <div class="h7 text-dark">
                                    <a href="{{ route('customer.ready.stock', ['stock' => encrypt((string)$s->id)]) }}">
                                        {{ $s->nama_produk ?? (strtoupper($s->brand).' '.number_format((float) ($s->gramasi ?? 0), 3).' gr') }}
                                    </a>
                                    <span class="dot"></span>
                                    <span class="body-6 text-dark-4">Harga: Rp {{ number_format((float) ($s->harga_jual_fix ?? $s->harga_jual_minimal ?? 0), 0) }}</span>
                                </div>
                                <div class="box-map-date">
                                    <div class="d-flex gap-4 align-items-center">
                                        <i class="icon icon-wallet-2 text-primary"></i>
                                        <span class="body-3 text-dark-4">Kode: {{ $s->kode_item }}</span>
                                    </div>
                                    <div class="d-flex gap-4 align-items-center">
                                        <i class="icon icon-package text-secondary-yellow"></i>
                                        <span class="body-3 text-dark-4">Stok: {{ (int) ($s->stok ?? 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-btn">
                            <a href="{{ route('customer.ready.stock', ['stock' => encrypt((string)$s->id)]) }}" class="btn-app button-1">Detail</a>
                            <a href="{{ route('customer.ready.buy', ['stock' => encrypt((string)$s->id)]) }}" class="btn-app button-1 view-app">Beli</a>
                            @php
                                $cartName = addslashes($s->nama_produk ?? (strtoupper($s->brand).' '.number_format((float)($s->gramasi ?? 0), 3).' gr'));
                                $cartPrice = (int) ($s->harga_jual_fix ?? $s->harga_jual_minimal ?? 0);
                                $cartImg = ($s->images && is_array($s->images) && count($s->images) > 0) ? (Str::startsWith($s->images[0], ['http://','https://']) ? $s->images[0] : asset($s->images[0])) : asset('front/images/golds/antam_2.jpg');
                                $cartStok = (int) ($s->stok ?? 0);
                            @endphp
                            <button class="btn-cart" onclick="rcAddToCart({{ $s->id }}, '{{ $cartName }}', {{ $cartPrice }}, '{{ $cartImg }}', {{ $cartStok }})">
                                <i class="icon-shopping-cart"></i> Keranjang
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">Belum ada stok tersedia.</div>
                @endforelse
            </div>
            <div class="p-2">
            </div>
        </div>
    </div>
        </div>
            </div>
    </div>
    @include('front.customer.partials.menubar-footer', ['active' => 'produk'])

    <!-- Cart Drawer -->
    <div class="rc-overlay" id="rcOverlay" onclick="toggleReadyCart()"></div>
    <div class="rc-drawer" id="rcDrawer">
        <div class="rc-header">
            <h4><i class="icon-shopping-cart" style="margin-right:8px;color:#dcb73f;"></i>Keranjang</h4>
            <button class="rc-close" onclick="toggleReadyCart()">&times;</button>
        </div>
        <div class="rc-body" id="rcBody">
            <div class="rc-empty">Keranjang masih kosong</div>
        </div>
        <div class="rc-footer" id="rcFooter" style="display:none;">
            <div class="rc-total">
                <span>Total</span>
                <span id="rcTotal">Rp 0</span>
            </div>
            <button class="rc-checkout-btn" onclick="window.location.href='{{ route('customer.dashboard') }}'">
                <i class="icon-arrow-right"></i> Checkout di Dashboard
            </button>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>

    <script>
    (function(){
        var CART_KEY = 'jj_cart';
        function getCart(){ try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch(e){ return []; } }
        function saveCart(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); }
        function fmtRp(n){ return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

        window.toggleReadyCart = function(){
            var o = document.getElementById('rcOverlay'), d = document.getElementById('rcDrawer');
            if(d.classList.contains('active')){ o.classList.remove('active'); d.classList.remove('active'); document.body.style.overflow=''; }
            else { o.classList.add('active'); d.classList.add('active'); document.body.style.overflow='hidden'; }
        };

        window.rcAddToCart = function(id, name, price, image, maxStok){
            var cart = getCart(), found = false;
            for(var i=0;i<cart.length;i++){
                if(cart[i].id===id){ if(cart[i].qty<maxStok) cart[i].qty++; found=true; break; }
            }
            if(!found) cart.push({id:id, name:name, price:price, image:image, qty:1, maxStok:maxStok});
            saveCart(cart); rcRender();
            // Open drawer
            document.getElementById('rcOverlay').classList.add('active');
            document.getElementById('rcDrawer').classList.add('active');
            document.body.style.overflow='hidden';
        };

        window.rcChangeQty = function(id, delta){
            var cart = getCart();
            for(var i=0;i<cart.length;i++){
                if(cart[i].id===id){ cart[i].qty+=delta; if(cart[i].qty<=0) cart.splice(i,1); else if(cart[i].qty>cart[i].maxStok) cart[i].qty=cart[i].maxStok; break; }
            }
            saveCart(cart); rcRender();
        };

        window.rcRemove = function(id){
            saveCart(getCart().filter(function(i){ return i.id!==id; })); rcRender();
        };

        function rcRender(){
            var cart=getCart(), body=document.getElementById('rcBody'), footer=document.getElementById('rcFooter'),
                badge=document.getElementById('rcBadge'), tc=0, tp=0;
            for(var i=0;i<cart.length;i++){ tc+=cart[i].qty; tp+=cart[i].qty*cart[i].price; }
            if(tc>0){ badge.textContent=tc>99?'99+':tc; badge.classList.add('has-items'); }
            else { badge.classList.remove('has-items'); }
            if(!cart.length){ body.innerHTML='<div class="rc-empty">Keranjang masih kosong</div>'; footer.style.display='none'; return; }
            var h='';
            for(var i=0;i<cart.length;i++){
                var c=cart[i];
                h+='<div class="rc-item"><div class="rc-item-img"><img src="'+c.image+'" alt="'+c.name+'"></div>'
                  +'<div class="rc-item-info"><div class="rc-item-name">'+c.name+'</div><div class="rc-item-price">'+fmtRp(c.price)+'</div>'
                  +'<div class="rc-item-qty"><button onclick="rcChangeQty('+c.id+',-1)">&minus;</button><span>'+c.qty+'</span><button onclick="rcChangeQty('+c.id+',1)">+</button></div></div>'
                  +'<button class="rc-item-rm" onclick="rcRemove('+c.id+')"><i class="icon-trash-2"></i></button></div>';
            }
            body.innerHTML=h; footer.style.display='block';
            document.getElementById('rcTotal').textContent=fmtRp(tp);
        }
        rcRender();
    })();
    </script>
</body>
</html>