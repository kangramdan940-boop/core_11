<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/styles.css') }}" />
    <link rel="shortcut icon" href="images/logo/168.png" />
    <title>All Order</title>
    <script>if(localStorage.toggled==="dark-theme"){document.documentElement.classList.add('dark-theme');}</script>
    <style>
        /* ===== Filter Tabs ===== */
        .ao-tabs {
            display:flex; gap:8px; overflow-x:auto;
            padding:12px 0 8px; margin-bottom:8px;
            -webkit-overflow-scrolling:touch;
            scrollbar-width:none;
        }
        .ao-tabs::-webkit-scrollbar { display:none; }
        .ao-tab {
            flex-shrink:0; padding:7px 16px;
            border-radius:25px; font-size:12px; font-weight:600;
            border:1px solid #e0e0e0; background:#fff; color:#666;
            cursor:pointer; transition:all .2s; white-space:nowrap;
            text-decoration:none;
        }
        .ao-tab:hover { border-color:#dcb73f; color:#dcb73f; }
        .ao-tab.active { background:#dcb73f; color:#fff; border-color:#dcb73f; }
        .ao-tab .ao-tab-count {
            display:inline-block; min-width:18px; height:18px; line-height:18px;
            text-align:center; border-radius:50%; font-size:10px; font-weight:700;
            margin-left:6px; background:rgba(0,0,0,.1); color:inherit;
        }
        .ao-tab.active .ao-tab-count { background:rgba(255,255,255,.3); color:#fff; }

        /* ===== Section Titles ===== */
        .ao-section-title {
            font-size:13px; font-weight:700; color:#999;
            text-transform:uppercase; letter-spacing:.5px;
            margin:18px 0 10px; padding-bottom:6px;
            border-bottom:1px solid #f0f0f0;
        }

        /* ===== Group (keranjang) ===== */
        .ao-group {
            background:#fff; border-radius:12px;
            box-shadow:0 1px 6px rgba(0,0,0,.05);
            margin-bottom:12px; overflow:hidden;
        }
        .ao-group-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:14px 16px; cursor:pointer; transition:background .2s;
        }
        .ao-group-header:hover { background:#fafafa; }
        .ao-group-left { display:flex; align-items:center; gap:12px; min-width:0; }
        .ao-group-icon {
            width:40px; height:40px; border-radius:10px;
            background:linear-gradient(135deg,#dcb73f,#c9a636);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:18px; flex-shrink:0;
        }
        .ao-group-info { min-width:0; }
        .ao-group-code { font-size:13px; font-weight:700; color:#333; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ao-group-meta { font-size:11px; color:#999; margin-top:2px; }
        .ao-group-right { display:flex; align-items:center; gap:10px; flex-shrink:0; }
        .ao-chevron { font-size:14px; color:#bbb; transition:transform .3s; }
        .ao-group.open .ao-chevron { transform:rotate(180deg); }
        .ao-group-body { max-height:0; overflow:hidden; transition:max-height .35s ease; border-top:0 solid #f0f0f0; }
        .ao-group.open .ao-group-body { border-top-width:1px; }
        .ao-group-body-inner { padding:0 16px 12px; }

        /* ===== Items inside group ===== */
        .ao-item {
            display:flex; align-items:center; gap:12px;
            padding:10px 0; border-bottom:1px solid #f5f5f5;
            text-decoration:none; color:inherit; transition:background .15s;
        }
        .ao-item:last-child { border-bottom:none; }
        .ao-item:hover { background:#fafafa; }
        .ao-item-img { width:44px; height:44px; border-radius:8px; overflow:hidden; flex-shrink:0; background:#f5f5f5; }
        .ao-item-img img { width:100%; height:100%; object-fit:cover; }
        .ao-item-info { flex:1; min-width:0; }
        .ao-item-title { font-size:13px; font-weight:600; color:#333; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ao-item-sub { font-size:11px; color:#999; margin-top:2px; }
        .ao-item-badge { flex-shrink:0; }

        /* ===== Single card (no keranjang) ===== */
        .ao-single {
            display:flex; align-items:center; gap:12px;
            background:#fff; border-radius:12px;
            box-shadow:0 1px 6px rgba(0,0,0,.05);
            padding:14px 16px; margin-bottom:10px;
            text-decoration:none; color:inherit; transition:box-shadow .2s;
        }
        .ao-single:hover { box-shadow:0 3px 12px rgba(0,0,0,.1); }
        .ao-single-icon { width:44px; height:44px; border-radius:10px; overflow:hidden; flex-shrink:0; background:#f5f5f5; }
        .ao-single-icon img { width:100%; height:100%; object-fit:cover; }
        .ao-single-info { flex:1; min-width:0; }
        .ao-single-title { font-size:13px; font-weight:600; color:#333; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ao-single-sub { font-size:11px; color:#999; margin-top:2px; }

        /* ===== Status Badges ===== */
        .ao-badge {
            display:inline-block; font-size:10px; font-weight:700; padding:3px 10px;
            border-radius:20px; text-transform:uppercase; letter-spacing:.3px; white-space:nowrap;
        }
        .ao-badge-pending_payment { background:#fff3cd; color:#856404; }
        .ao-badge-perlu_dibayar   { background:#fff3cd; color:#856404; }
        .ao-badge-paid            { background:#d1ecf1; color:#0c5460; }
        .ao-badge-processing      { background:#cce5ff; color:#004085; }
        .ao-badge-ready_at_agen   { background:#e2d6f3; color:#5a2d82; }
        .ao-badge-shipped         { background:#d4edda; color:#155724; }
        .ao-badge-completed       { background:#c3e6cb; color:#0b5226; }
        .ao-badge-cancelled       { background:#f8d7da; color:#721c24; }
        .ao-badge-active          { background:#d1ecf1; color:#0c5460; }
        .ao-badge-unknown         { background:#e2e3e5; color:#383d41; }

        /* ===== Empty ===== */
        .ao-empty { text-align:center; color:#aaa; padding:60px 20px; }
        .ao-empty i { font-size:40px; display:block; margin-bottom:12px; }

        /* Hide filtered items */
        .ao-hidden { display:none !important; }
    </style>
</head>
<body>
    <div class="preload preload-container">
        <div class="logo-img"><img src="{{ asset('front/images/logo/logo-dark2.png') }}" alt=""></div>
        <div class="spinner-circle lg success">
            <span class="spinner-circle1 spinner-child"></span><span class="spinner-circle2 spinner-child"></span>
            <span class="spinner-circle3 spinner-child"></span><span class="spinner-circle4 spinner-child"></span>
            <span class="spinner-circle5 spinner-child"></span><span class="spinner-circle6 spinner-child"></span>
            <span class="spinner-circle7 spinner-child"></span><span class="spinner-circle8 spinner-child"></span>
            <span class="spinner-circle9 spinner-child"></span>
        </div>
    </div>

    <div class="header fixed-top">
        <div class="left">
            <a href="javascript:void(0);" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
        <h3>All Order</h3>
    </div>

    <div class="app-content style-4">
        <div class="tf-container">
            <div class="mt-24 mb-100pc">

                @php
                    $totalTrans = ($orders->count() + $readyOrders->count() + $contracts->count());

                    // Collect all statuses for counting
                    $allStatuses = collect();
                    foreach($orders as $o) { $allStatuses->push(strtolower($o->status)); }
                    foreach($readyOrders as $r) { $allStatuses->push(strtolower($r->status)); }
                    foreach($contracts as $c) { $allStatuses->push(strtolower($c->status)); }
                    $statusCounts = $allStatuses->countBy();

                    $filterTabs = [
                        'all' => 'Semua',
                        'pending_payment' => 'Pending',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'ready_at_agen' => 'Ready Agen',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ];
                @endphp

                {{-- Filter Tabs --}}
                <div class="ao-tabs">
                    @foreach($filterTabs as $key => $label)
                        @php $count = $key === 'all' ? $totalTrans : ($statusCounts[$key] ?? 0); @endphp
                        @if($key === 'all' || $count > 0)
                        <div class="ao-tab {{ $key === 'all' ? 'active' : '' }}" data-filter="{{ $key }}" onclick="aoFilter(this)">
                            {{ $label }}<span class="ao-tab-count">{{ $count }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- Empty state --}}
                <div class="ao-empty ao-no-results" style="display:none;">
                    <i class="icon-inbox"></i>
                    <p>Tidak ada order dengan status ini.</p>
                </div>

                @if($totalTrans === 0)
                    <div class="ao-empty">
                        <i class="icon-inbox"></i>
                        <p>Belum ada transaksi.</p>
                    </div>
                @else

                    {{-- ===== READY: Grouped by Keranjang ===== --}}
                    @if($readyGrouped->count())
                        <div class="ao-section-title ao-section" data-section="ready-grouped">🛒 Keranjang Emas Ready</div>
                        @foreach($readyGrouped as $keranjangId => $items)
                            @php
                                $krj = $items->first()->keranjang;
                                $kode = $krj->kode_keranjang ?? 'Keranjang #'.$keranjangId;
                                $statusKrj = strtolower($krj->status_order ?? 'unknown');
                                $totalAmount = $items->sum('total_amount');
                                $totalQty = $items->sum('qty');
                                $createdAt = optional($krj->created_at)->format('d M Y H:i') ?? '-';
                                $itemStatuses = $items->pluck('status')->map(fn($s) => strtolower($s))->unique()->implode(' ');
                            @endphp
                            <div class="ao-group ao-filterable" data-statuses="{{ $itemStatuses }} {{ $statusKrj }}" data-section="ready-grouped" data-collapse>
                                <div class="ao-group-header" onclick="toggleAoGroup(this)">
                                    <div class="ao-group-left">
                                        <div class="ao-group-icon"><i class="icon-shopping-cart"></i></div>
                                        <div class="ao-group-info">
                                            <div class="ao-group-code">{{ $kode }}</div>
                                            <div class="ao-group-meta">{{ $totalQty }} item · Rp {{ number_format($totalAmount, 0, ',', '.') }} · {{ $createdAt }}</div>
                                        </div>
                                    </div>
                                    <div class="ao-group-right">
                                        <span class="ao-badge ao-badge-{{ $statusKrj }}">{{ strtoupper(str_replace('_', ' ', $statusKrj)) }}</span>
                                        <span class="ao-chevron">▼</span>
                                    </div>
                                </div>
                                <div class="ao-group-body">
                                    <div class="ao-group-body-inner">
                                        @foreach($items as $r)
                                            @php
                                                $imgs = $r->readyStock->images ?? [];
                                                $img = is_array($imgs) && count($imgs) ? $imgs[0] : null;
                                                $imgUrl = $img ? (str_starts_with($img, 'http') ? $img : asset($img)) : asset('front/images/golds/antam_2.jpg');
                                            @endphp
                                            <a href="{{ route('customer.ready.show', ['ready' => encrypt((string)$r->id)]) }}" class="ao-item">
                                                <div class="ao-item-img"><img src="{{ $imgUrl }}" alt=""></div>
                                                <div class="ao-item-info">
                                                    <div class="ao-item-title">{{ $r->readyStock->brand ?? 'Emas Ready' }} {{ number_format((float)($r->readyStock->gramasi ?? 0), 3) }}gr</div>
                                                    <div class="ao-item-sub">{{ $r->kode_trans }} · {{ $r->qty }}pcs · Rp {{ number_format((float)$r->total_amount, 0, ',', '.') }}</div>
                                                </div>
                                                <div class="ao-item-badge"><span class="ao-badge ao-badge-{{ strtolower($r->status) }}">{{ strtoupper(str_replace('_', ' ', $r->status)) }}</span></div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- ===== READY: Single ===== --}}
                    @if($readySingle->count())
                        <div class="ao-section-title ao-section" data-section="ready-single">📦 Emas Ready</div>
                        @foreach($readySingle as $r)
                            @php
                                $imgs = $r->readyStock->images ?? [];
                                $img = is_array($imgs) && count($imgs) ? $imgs[0] : null;
                                $imgUrl = $img ? (str_starts_with($img, 'http') ? $img : asset($img)) : asset('front/images/golds/antam_2.jpg');
                            @endphp
                            <a href="{{ route('customer.ready.show', ['ready' => encrypt((string)$r->id)]) }}" class="ao-single ao-filterable" data-statuses="{{ strtolower($r->status) }}" data-section="ready-single">
                                <div class="ao-single-icon"><img src="{{ $imgUrl }}" alt=""></div>
                                <div class="ao-single-info">
                                    <div class="ao-single-title">{{ $r->readyStock->brand ?? 'Emas Ready' }} {{ number_format((float)($r->readyStock->gramasi ?? 0), 3) }}gr</div>
                                    <div class="ao-single-sub">{{ $r->kode_trans }} · {{ $r->qty }}pcs · Rp {{ number_format((float)$r->total_amount, 0, ',', '.') }}</div>
                                </div>
                                <span class="ao-badge ao-badge-{{ strtolower($r->status) }}">{{ strtoupper(str_replace('_', ' ', $r->status)) }}</span>
                            </a>
                        @endforeach
                    @endif

                    {{-- ===== PO: Grouped ===== --}}
                    @if($poGrouped->count())
                        <div class="ao-section-title ao-section" data-section="po-grouped">🛒 Keranjang Pre Order</div>
                        @foreach($poGrouped as $keranjangId => $items)
                            @php
                                $krj = $items->first()->keranjang;
                                $kode = $krj->kode_keranjang ?? 'Keranjang #'.$keranjangId;
                                $statusKrj = strtolower($krj->status_order ?? 'unknown');
                                $totalAmount = $items->sum('total_amount');
                                $totalQty = $items->sum('qty');
                                $createdAt = optional($krj->created_at)->format('d M Y H:i') ?? '-';
                                $itemStatuses = $items->pluck('status')->map(fn($s) => strtolower($s))->unique()->implode(' ');
                            @endphp
                            <div class="ao-group ao-filterable" data-statuses="{{ $itemStatuses }} {{ $statusKrj }}" data-section="po-grouped" data-collapse>
                                <div class="ao-group-header" onclick="toggleAoGroup(this)">
                                    <div class="ao-group-left">
                                        <div class="ao-group-icon" style="background:linear-gradient(135deg,#3498db,#2980b9);"><i class="icon-shopping-bag"></i></div>
                                        <div class="ao-group-info">
                                            <div class="ao-group-code">{{ $kode }}</div>
                                            <div class="ao-group-meta">{{ $totalQty }} item · Rp {{ number_format($totalAmount, 0, ',', '.') }} · {{ $createdAt }}</div>
                                        </div>
                                    </div>
                                    <div class="ao-group-right">
                                        <span class="ao-badge ao-badge-{{ $statusKrj }}">{{ strtoupper(str_replace('_', ' ', $statusKrj)) }}</span>
                                        <span class="ao-chevron">▼</span>
                                    </div>
                                </div>
                                <div class="ao-group-body">
                                    <div class="ao-group-body-inner">
                                        @foreach($items as $o)
                                            <a href="{{ route('customer.po.show', encrypt($o->id)) }}" class="ao-item">
                                                <div class="ao-item-img"><img src="{{ asset('front/images/golds/antam_1.jpg') }}" alt=""></div>
                                                <div class="ao-item-info">
                                                    <div class="ao-item-title">PO {{ $o->kode_po }}</div>
                                                    <div class="ao-item-sub">{{ number_format((float)$o->total_gram, 3) }}gr · Rp {{ number_format((float)$o->total_amount, 0, ',', '.') }}</div>
                                                </div>
                                                <div class="ao-item-badge"><span class="ao-badge ao-badge-{{ strtolower($o->status) }}">{{ strtoupper(str_replace('_', ' ', $o->status)) }}</span></div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- ===== PO: Single ===== --}}
                    @if($poSingle->count())
                        <div class="ao-section-title ao-section" data-section="po-single">📋 Pre Order</div>
                        @foreach($poSingle as $o)
                            <a href="{{ route('customer.po.show', encrypt($o->id)) }}" class="ao-single ao-filterable" data-statuses="{{ strtolower($o->status) }}" data-section="po-single">
                                <div class="ao-single-icon"><img src="{{ asset('front/images/golds/antam_1.jpg') }}" alt=""></div>
                                <div class="ao-single-info">
                                    <div class="ao-single-title">PO {{ $o->kode_po }} · {{ number_format((float)$o->total_gram, 3) }}gr</div>
                                    <div class="ao-single-sub">Rp {{ number_format((float)$o->total_amount, 0, ',', '.') }} · {{ optional($o->ordered_at)->format('d M Y') ?? '-' }}</div>
                                </div>
                                <span class="ao-badge ao-badge-{{ strtolower($o->status) }}">{{ strtoupper(str_replace('_', ' ', $o->status)) }}</span>
                            </a>
                        @endforeach
                    @endif

                    {{-- ===== CICILAN ===== --}}
                    @if($contracts->count())
                        <div class="ao-section-title ao-section" data-section="cicilan">💰 Cicilan</div>
                        @foreach($contracts as $c)
                            <a href="{{ route('customer.cicilan.show', $c) }}" class="ao-single ao-filterable" data-statuses="{{ strtolower($c->status) }}" data-section="cicilan">
                                <div class="ao-single-icon"><img src="{{ asset('front/images/golds/antam_5.jpg') }}" alt=""></div>
                                <div class="ao-single-info">
                                    <div class="ao-single-title">Kontrak {{ $c->kode_kontrak }} · {{ number_format((float)$c->gramasi, 3) }}gr</div>
                                    <div class="ao-single-sub">{{ optional($c->created_at)->format('d M Y') ?? '-' }}</div>
                                </div>
                                <span class="ao-badge ao-badge-{{ strtolower($c->status) }}">{{ strtoupper(str_replace('_', ' ', $c->status)) }}</span>
                            </a>
                        @endforeach
                    @endif

                @endif
            </div>
        </div>
    </div>

    @include('front.customer.partials.menubar-footer', ['active' => 'all-order', 'poHref' => 'pre-order-emas'])

    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/nouislider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/rangle-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js') }}"></script>

    <script>
    function toggleAoGroup(header) {
        var group = header.closest('.ao-group');
        var body = group.querySelector('.ao-group-body');
        if (group.classList.contains('open')) {
            body.style.maxHeight = null;
            group.classList.remove('open');
        } else {
            body.style.maxHeight = body.scrollHeight + 'px';
            group.classList.add('open');
        }
    }

    function aoFilter(tab) {
        // Update active tab
        var tabs = document.querySelectorAll('.ao-tab');
        for (var i = 0; i < tabs.length; i++) tabs[i].classList.remove('active');
        tab.classList.add('active');

        var filter = tab.getAttribute('data-filter');
        var items = document.querySelectorAll('.ao-filterable');
        var sections = document.querySelectorAll('.ao-section');
        var visibleCount = 0;

        // Show/hide items
        for (var i = 0; i < items.length; i++) {
            var el = items[i];
            if (filter === 'all') {
                el.classList.remove('ao-hidden');
                visibleCount++;
            } else {
                var statuses = el.getAttribute('data-statuses') || '';
                if (statuses.indexOf(filter) !== -1) {
                    el.classList.remove('ao-hidden');
                    visibleCount++;
                } else {
                    el.classList.add('ao-hidden');
                    // Close group if hidden
                    if (el.classList.contains('ao-group') && el.classList.contains('open')) {
                        el.classList.remove('open');
                        var body = el.querySelector('.ao-group-body');
                        if (body) body.style.maxHeight = null;
                    }
                }
            }
        }

        // Show/hide section titles based on visible children
        for (var i = 0; i < sections.length; i++) {
            var sec = sections[i].getAttribute('data-section');
            var hasVisible = false;
            var children = document.querySelectorAll('.ao-filterable[data-section="' + sec + '"]');
            for (var j = 0; j < children.length; j++) {
                if (!children[j].classList.contains('ao-hidden')) { hasVisible = true; break; }
            }
            sections[i].style.display = hasVisible ? '' : 'none';
        }

        // Show empty state if nothing visible
        var noResults = document.querySelector('.ao-no-results');
        if (noResults) noResults.style.display = visibleCount === 0 ? '' : 'none';
    }
    </script>
</body>
</html>
