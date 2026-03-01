<ul class="main-menu" id="all-menu-items" role="menu">
    @php
        $menu = [
            [ 'type' => 'title', 'text' => 'Main' ],
            [ 'type' => 'link', 'icon' => 'ri-home-3-line', 'text' => 'Dashboard', 'url' => route('admin.dashboard'), 'routeIs' => 'admin.dashboard' ],
            [ 'type' => 'link', 'icon' => 'ri-file-chart-line', 'text' => 'Laporan Keuangan', 'url' => route('admin.reports.index'), 'routeIs' => 'admin.reports.*' ],
            [ 'type' => 'link', 'icon' => 'ri-mail-send-line', 'text' => 'Email Logs', 'url' => route('admin.email-logs.index'), 'routeIs' => 'admin.email-logs.*' ],
            [ 'type' => 'link', 'icon' => 'ri-receipt-line', 'text' => 'Payment Log', 'url' => route('admin.trans.payment-logs.index'), 'routeIs' => 'admin.trans.payment-logs.*' ],

 [ 'type' => 'title', 'text' => 'Transaksi' ],
            [ 'type' => 'link', 'icon' => 'ri-file-text-line', 'text' => 'Faktur Emas', 'url' => route('admin.master.faktur.index'), 'routeIs' => 'admin.master.faktur.*' ],
            [ 'type' => 'link', 'icon' => 'ri-file-list-3-line', 'text' => 'PO Emas', 'url' => route('admin.trans.po.index', ['date' => 'today']), 'routeIs' => 'admin.trans.po.*' ],
            [ 'type' => 'link', 'icon' => 'ri-shopping-cart-2-line', 'text' => 'Keranjang', 'url' => route('admin.trans.keranjang.index'), 'routeIs' => 'admin.trans.keranjang.*' ],
            [ 'type' => 'link', 'icon' => 'ri-archive-stack-line', 'text' => 'Stok Emas Ready', 'url' => route('admin.master.ready-stocks.index'), 'routeIs' => 'admin.master.ready-stocks.*' ],
            [ 'type' => 'link', 'icon' => 'ri-calculator-line', 'text' => 'Kalkulator FIFO', 'url' => route('admin.trans.fifo-calculator'), 'routeIs' => 'admin.trans.fifo-calculator' ],
            [ 'type' => 'link', 'icon' => 'ri-shopping-bag-3-line', 'text' => 'Produk & Layanan', 'url' => route('admin.master.produk-layanan.index'), 'routeIs' => 'admin.master.produk-layanan.*' ],
            [ 'type' => 'link', 'icon' => 'ri-flashlight-line', 'text' => 'Emas Ready', 'url' => route('admin.trans.ready.index'), 'routeIs' => 'admin.trans.ready.*' ],
            [ 'type' => 'link', 'icon' => 'ri-flashlight-fill', 'text' => 'Flash Sale Order', 'url' => route('admin.trans.flash-sale-orders.index'), 'routeIs' => 'admin.trans.flash-sale-orders.*' ],
            [ 'type' => 'title', 'text' => 'Cicilan' ],
            [ 'type' => 'group', 'icon' => 'ri-hand-coin-line', 'text' => 'Cicilan', 'children' => [
                [ 'type' => 'link', 'icon' => 'ri-hand-heart-line', 'text' => 'Layanan Emas Cicilan', 'url' => route('admin.master.layanan-emas-cicilan.index'), 'routeIs' => 'admin.master.layanan-emas-cicilan.*' ],
                [ 'type' => 'link', 'icon' => 'ri-scales-3-line', 'text' => 'Cicilan Emas Dibuka', 'url' => route('admin.trans.cicilan-emas.index'), 'routeIs' => 'admin.trans.cicilan-emas.*' ],
                [ 'type' => 'link', 'icon' => 'ri-file-list-3-line', 'text' => 'Kontrak Cicilan', 'url' => route('admin.trans.cicilan.index'), 'routeIs' => 'admin.trans.cicilan.*' ],
                [ 'type' => 'link', 'icon' => 'ri-article-line', 'text' => 'Akad Murabahah', 'url' => route('admin.trans.cicilan-akad.index'), 'routeIs' => 'admin.trans.cicilan-akad.*' ],
                [ 'type' => 'link', 'icon' => 'ri-bank-card-line', 'text' => 'Pembayaran Cicilan', 'url' => route('admin.trans.cicilan-payments.index'), 'routeIs' => 'admin.trans.cicilan-payments.*' ],
                [ 'type' => 'link', 'icon' => 'ri-alarm-warning-line', 'text' => 'Tagihan Jatuh Tempo', 'url' => route('admin.trans.cicilan-payments.overdue'), 'routeIs' => 'admin.trans.cicilan-payments.overdue' ],
            ] ],
           
           

            [ 'type' => 'title', 'text' => 'Master' ],
            [ 'type' => 'link', 'icon' => 'ri-user-3-line', 'text' => 'Customer', 'url' => route('admin.master.customers.index'), 'routeIs' => 'admin.master.customers.*' ],
            [ 'type' => 'link', 'icon' => 'ri-map-pin-line', 'text' => 'Alamat Customer', 'url' => route('admin.master.customer-addresses.index'), 'routeIs' => 'admin.master.customer-addresses.*' ],
            [ 'type' => 'link', 'icon' => 'ri-price-tag-3-line', 'text' => 'Brand Emas', 'url' => route('admin.master.brand-emas.index'), 'routeIs' => 'admin.master.brand-emas.*' ],
            [ 'type' => 'link', 'icon' => 'ri-scales-3-line', 'text' => 'Gramasi Emas', 'url' => route('admin.master.gramasi-emas.index'), 'routeIs' => 'admin.master.gramasi-emas.*' ],
            [ 'type' => 'link', 'icon' => 'ri-image-line', 'text' => 'Home Slider', 'url' => route('admin.master.home-slider.index'), 'routeIs' => 'admin.master.home-slider.*' ],
            [ 'type' => 'link', 'icon' => 'ri-apps-2-line', 'text' => 'Menu Home Customer', 'url' => route('admin.master.menu-home-customer.index'), 'routeIs' => 'admin.master.menu-home-customer.*' ],
            [ 'type' => 'link', 'icon' => 'ri-group-line', 'text' => 'Agen', 'url' => route('admin.master.agens.index'), 'routeIs' => 'admin.master.agens.*' ],
            [ 'type' => 'link', 'icon' => 'ri-bank-line', 'text' => 'Mitra Brankas', 'url' => route('admin.master.mitra-brankas.index'), 'routeIs' => 'admin.master.mitra-brankas.*' ],
            [ 'type' => 'link', 'icon' => 'ri-shield-user-line', 'text' => 'Admin', 'url' => route('admin.master.admins.index'), 'routeIs' => 'admin.master.admins.*' ],
            [ 'type' => 'link', 'icon' => 'ri-folder-line', 'text' => 'Master Asset', 'url' => route('admin.master.assets.index'), 'routeIs' => 'admin.master.assets.*' ],
            [ 'type' => 'link', 'icon' => 'ri-flashlight-fill', 'text' => 'Flash Sale', 'url' => route('admin.master.flash-sales.index'), 'routeIs' => 'admin.master.flash-sales.*' ],
            // [ 'type' => 'link', 'icon' => 'ri-archive-stack-line', 'text' => 'Stok Emas Antam', 'url' => route('admin.master.gold-stocks.index'), 'routeIs' => 'admin.master.gold-stocks.*', 'roles' => ['agen', 'admin', 'super_admin'] ],
            [ 'type' => 'title', 'text' => 'Emas' ],
            [ 'type' => 'link', 'icon' => 'ri-bar-chart-2-line', 'text' => 'Harga Emas', 'url' => route('admin.master.gold-prices.index'), 'routeIs' => 'admin.master.gold-prices.*' ],
            [ 'type' => 'link', 'icon' => 'ri-hand-coin-line', 'text' => 'Komisi Mitra', 'url' => route('admin.master.mitra-komisi.index'), 'routeIs' => 'admin.master.mitra-komisi.*' ],


            [ 'type' => 'title', 'text' => 'System' ],
            [ 'type' => 'link', 'icon' => 'ri-team-line', 'text' => 'Management Login List', 'url' => route('admin.login-management.index'), 'routeIs' => 'admin.login-management.*' ],
            [ 'type' => 'link', 'icon' => 'ri-settings-3-line', 'text' => 'Setting', 'url' => route('admin.master.settings.index'), 'routeIs' => 'admin.master.settings.*' ],
            [ 'type' => 'link', 'icon' => 'ri-smartphone-line', 'text' => 'Management Mobile Apps', 'url' => route('admin.master.mobile-app-configs.index'), 'routeIs' => 'admin.master.mobile-app-configs.*' ],
            [ 'type' => 'link', 'icon' => 'ri-bank-card-line', 'text' => 'Management Payment', 'url' => route('admin.master.payment-settings.index'), 'routeIs' => 'admin.master.payment-settings.*' ],
            [ 'type' => 'link', 'icon' => 'ri-user-settings-line', 'text' => 'Role', 'url' => '#!' ],
            [ 'type' => 'link', 'icon' => 'ri-notification-3-line', 'text' => 'Notifikasi', 'url' => route('admin.notifications.index'), 'routeIs' => 'admin.notifications.*' ],
        ];

        $renderMenuItem = function ($item) use (&$renderMenuItem) {
            $html = '';
            $type = $item['type'] ?? '';
            $allowedRoles = $item['roles'] ?? null;
            if ($allowedRoles !== null) {
                $currentRole = auth()->user()->role ?? null;
                if (!$currentRole || !in_array($currentRole, (array) $allowedRoles, true)) {
                    return '';
                }
            }

            if ($type === 'title') {
                $html .= '<li class="menu-title" role="presentation">'.e($item['text'] ?? '').'</li>';
            } elseif ($type === 'group') {
                $isActiveGroup = false;
                if (!empty($item['children']) && is_array($item['children'])) {
                    foreach ($item['children'] as $child) {
                        $routeIs = $child['routeIs'] ?? null;
                        if ($routeIs && request()->routeIs($routeIs)) { $isActiveGroup = true; break; }
                    }
                }
                $liClass = 'slide' . ($isActiveGroup ? ' open-menu' : '');
                $aClass  = 'side-menu__item app-menu-item' . ($isActiveGroup ? ' active' : '');
                $html .= '<li class="'.$liClass.'">';
                $html .= '<a href="#!" class="'.$aClass.'" role="menuitem">';
                if (!empty($item['icon'])) {
                    $html .= '<span class="side_menu_icon"><i class="'.htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8').'"></i></span>';
                }
                $html .= '<span class="side-menu__label">'.e($item['text'] ?? '').'</span>';
                if (!empty($item['children'])) {
                    $html .= '<i class="ri-arrow-down-s-line side-menu__angle"></i>';
                }
                $html .= '</a>';
                if (!empty($item['children']) && is_array($item['children'])) {
                    $html .= '<ul class="slide-menu" role="menu">';
                    foreach ($item['children'] as $child) {
                        $html .= $renderMenuItem($child);
                    }
                    $html .= '</ul>';
                }
                $html .= '</li>';
            } elseif ($type === 'link') {
                $url = !empty($item['url']) ? $item['url'] : '#!';
                $active = !empty($item['routeIs']) && request()->routeIs($item['routeIs']) ? ' active' : '';
                $html .= '<li class="slide">';
                $html .= '<a href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'" class="side-menu__item app-menu-item'.$active.'" role="menuitem">';
                if (!empty($item['icon'])) {
                    $html .= '<span class="side_menu_icon"><i class="'.htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8').'"></i></span>';
                }
                $html .= '<span class="side-menu__label">'.e($item['text'] ?? '').'</span>';
                $html .= '</a>';
                $html .= '</li>';
            }

            return $html;
        };
    @endphp

    @foreach($menu as $item)
        {!! $renderMenuItem($item) !!}
    @endforeach
</ul>
