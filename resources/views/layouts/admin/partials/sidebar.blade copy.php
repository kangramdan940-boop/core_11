<!-- START SIDEBAR -->
<aside class="app-sidebar">
    <!-- START BRAND LOGO -->
    <div class="app-sidebar-logo px-6 justify-content-center align-items-center">
        <a href="index">
            <img height="35" class="app-sidebar-logo-default" alt="Logo" src="{{ asset('assets/images/light-logo.png') }}">
            <img height="40" class="app-sidebar-logo-minimize" alt="Logo" src="{{ asset('assets/images/Favicon.png') }}">
        </a>
    </div>
    <!-- END BRAND LOGO -->
    <nav class="app-sidebar-menu nav nav-pills flex-column fs-6 simplebar simplebar-scrollable-y" id="sidebarMenu" aria-label="Main navigation">
        <ul class="main-menu list-unstyled mb-0">
            <li class="menu-title px-3 pt-2 text-muted">Main</li>
            <li class="slide">
                <a href="javascript:void(0)" class="side-menu__item app-menu-item" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-home-2-line"></i></span>
                    <span class="side-menu__label" data-lang="hr-dashboards">Dashboards</span>
                    <i class="ri-arrow-down-s-line side-menu__angle"></i>
                </a>
                <ul class="slide-menu" role="menu">
                    <li class="slide">
                        <a href="index.html" class="side-menu__item" role="menuitem" data-lang="hr-dashboards-ecommerce">E-Commerce</a>
                    </li>
                    <li class="slide">
                        <a href="dashboard-project-management.html" data-lang="hr-dashboards-project-management" class="side-menu__item" role="menuitem">Project Management</a>
                    </li>
                    <li class="slide">
                        <a href="dashboard-online-course.html" data-lang="hr-dashboards-online-course" class="side-menu__item" role="menuitem">Online course</a>
                    </li>
                    <li class="slide">
                        <a href="dashboard-social-media.html" data-lang="hr-dashboards-social-media" class="side-menu__item" role="menuitem">Social Media</a>
                    </li>
                </ul>
            </li>
            <li class="slide">
                <a href="{{ route('admin.dashboard') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-home-3-line"></i></span>
                    <span class="side-menu__label">Dashboard</span>
                </a>
            </li>

            <li class="menu-title px-3 pt-3 text-muted">Master</li>
            <li class="slide">
                <a href="{{ route('admin.master.customers.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.customers.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-user-3-line"></i></span>
                    <span class="side-menu__label">Customer</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.brand-emas.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.brand-emas.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-price-tag-3-line"></i></span>
                    <span class="side-menu__label">Brand Emas</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.gramasi-emas.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.gramasi-emas.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-scales-3-line"></i></span>
                    <span class="side-menu__label">Gramasi Emas</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.produk-layanan.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.produk-layanan.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-shopping-bag-3-line"></i></span>
                    <span class="side-menu__label">Produk & Layanan</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.home-slider.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.home-slider.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-image-line"></i></span>
                    <span class="side-menu__label">Home Slider</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.menu-home-customer.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.menu-home-customer.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-apps-2-line"></i></span>
                    <span class="side-menu__label">Menu Home Customer</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.agens.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.agens.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-group-line"></i></span>
                    <span class="side-menu__label">Agen</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.mitra-brankas.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.mitra-brankas.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-bank-line"></i></span>
                    <span class="side-menu__label">Mitra Brankas</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.admins.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.admins.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-shield-user-line"></i></span>
                    <span class="side-menu__label">Admin</span>
                </a>
            </li>

            <li class="menu-title px-3 pt-3 text-muted">Emas</li>
            <li class="slide">
                <a href="{{ route('admin.master.gold-prices.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.gold-prices.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-bar-chart-2-line"></i></span>
                    <span class="side-menu__label">Harga Emas</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.ready-stocks.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.ready-stocks.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-archive-stack-line"></i></span>
                    <span class="side-menu__label">Stok Emas Ready</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.master.mitra-komisi.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.mitra-komisi.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-hand-coin-line"></i></span>
                    <span class="side-menu__label">Komisi Mitra</span>
                </a>
            </li>

            <li class="menu-title px-3 pt-3 text-muted">Transaksi</li>
            <li class="slide">
                <a href="{{ route('admin.trans.po.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.trans.po.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-file-list-3-line"></i></span>
                    <span class="side-menu__label">PO Emas</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.trans.ready.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.trans.ready.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-flashlight-line"></i></span>
                    <span class="side-menu__label">Emas Ready</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.trans.cicilan.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.trans.cicilan.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-calendar-check-line"></i></span>
                    <span class="side-menu__label">Cicilan Emas</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.trans.cicilan-payments.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.trans.cicilan-payments.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-credit-card-2-line"></i></span>
                    <span class="side-menu__label">Pembayaran Cicilan</span>
                </a>
            </li>

            <li class="menu-title px-3 pt-3 text-muted">System</li>
            <li class="slide">
                <a href="{{ route('admin.master.settings.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.master.settings.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-settings-3-line"></i></span>
                    <span class="side-menu__label">Setting</span>
                </a>
            </li>
            <li class="slide">
                <a href="#" class="side-menu__item app-menu-item" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-user-settings-line"></i></span>
                    <span class="side-menu__label">Role</span>
                </a>
            </li>
            <li class="slide">
                <a href="#" class="side-menu__item app-menu-item" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-notification-3-line"></i></span>
                    <span class="side-menu__label">Notifikasi</span>
                </a>
            </li>
            <li class="slide">
                <a href="{{ route('admin.trans.payment-logs.index') }}" class="side-menu__item app-menu-item {{ request()->routeIs('admin.trans.payment-logs.*') ? 'active' : '' }}" role="menuitem">
                    <span class="side-menu__icon"><i class="ri-receipt-line"></i></span>
                    <span class="side-menu__label">Payment Log</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<!-- END SIDEBAR -->
<div class="horizontal-overlay"></div>

<!-- START SMALL SCREEN SIDEBAR -->
<div class="offcanvas offcanvas-md offcanvas-start small-screen-sidebar" data-bs-scroll="true" tabindex="-1" id="smallScreenSidebar" aria-labelledby="smallScreenSidebarLabel">
    <div class="offcanvas-header hstack border-bottom">
        <!-- START BRAND LOGO -->
        <div class="app-sidebar-logo">
            <a href="index">
                <img height="35" class="app-sidebar-logo-default h-25px" alt="Logo" src="{{ asset('assets/images/light-logo.png') }}">
                <img height="40" class="app-sidebar-logo-minimize" alt="Logo" src="{{ asset('assets/images/Favicon.png') }}">
            </a>
        </div>
        <button type="button" class="btn-close bg-transparent" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="ri-close-line"></i>
        </button>
    </div>
    <div class="offcanvas-body p-0">
        <!-- START SIDEBAR -->
        <aside class="app-sidebar">
            <!-- END BRAND LOGO -->
            <nav class="app-sidebar-menu nav nav-pills flex-column fs-6 simplebar simplebar-scrollable-y" aria-label="Main navigation">
                <ul class="main-menu list-unstyled mb-0">
                    <li class="menu-title px-3 pt-2 text-muted">Main</li>
                    <li class="slide">
                        <a href="{{ route('admin.dashboard') }}" class="side-menu__item app-menu-item" role="menuitem">
                            <span class="side-menu__icon"><i class="ri-home-3-line"></i></span>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-title px-3 pt-3 text-muted">Master</li>
                    <li class="slide"><a href="{{ route('admin.master.customers.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-user-3-line"></i></span><span class="side-menu__label">Customer</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.brand-emas.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-price-tag-3-line"></i></span><span class="side-menu__label">Brand Emas</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.gramasi-emas.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-scales-3-line"></i></span><span class="side-menu__label">Gramasi Emas</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.produk-layanan.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-shopping-bag-3-line"></i></span><span class="side-menu__label">Produk & Layanan</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.home-slider.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-image-line"></i></span><span class="side-menu__label">Home Slider</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.menu-home-customer.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-apps-2-line"></i></span><span class="side-menu__label">Menu Home Customer</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.agens.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-group-line"></i></span><span class="side-menu__label">Agen</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.mitra-brankas.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-bank-line"></i></span><span class="side-menu__label">Mitra Brankas</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.admins.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-shield-user-line"></i></span><span class="side-menu__label">Admin</span></a></li>
                    <li class="menu-title px-3 pt-3 text-muted">Emas</li>
                    <li class="slide"><a href="{{ route('admin.master.gold-prices.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-bar-chart-2-line"></i></span><span class="side-menu__label">Harga Emas</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.ready-stocks.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-archive-stack-line"></i></span><span class="side-menu__label">Stok Emas Ready</span></a></li>
                    <li class="slide"><a href="{{ route('admin.master.mitra-komisi.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-hand-coin-line"></i></span><span class="side-menu__label">Komisi Mitra</span></a></li>
                    <li class="menu-title px-3 pt-3 text-muted">Transaksi</li>
                    <li class="slide"><a href="{{ route('admin.trans.po.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-file-list-3-line"></i></span><span class="side-menu__label">PO Emas</span></a></li>
                    <li class="slide"><a href="{{ route('admin.trans.ready.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-flashlight-line"></i></span><span class="side-menu__label">Emas Ready</span></a></li>
                    <li class="slide"><a href="{{ route('admin.trans.cicilan.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-calendar-check-line"></i></span><span class="side-menu__label">Cicilan Emas</span></a></li>
                    <li class="slide"><a href="{{ route('admin.trans.cicilan-payments.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-credit-card-2-line"></i></span><span class="side-menu__label">Pembayaran Cicilan</span></a></li>
                    <li class="menu-title px-3 pt-3 text-muted">System</li>
                    <li class="slide"><a href="{{ route('admin.master.settings.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-settings-3-line"></i></span><span class="side-menu__label">Setting</span></a></li>
                    <li class="slide"><a href="#" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-user-settings-line"></i></span><span class="side-menu__label">Role</span></a></li>
                    <li class="slide"><a href="#" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-notification-3-line"></i></span><span class="side-menu__label">Notifikasi</span></a></li>
                    <li class="slide"><a href="{{ route('admin.trans.payment-logs.index') }}" class="side-menu__item app-menu-item" role="menuitem"><span class="side-menu__icon"><i class="ri-receipt-line"></i></span><span class="side-menu__label">Payment Log</span></a></li>
                </ul>
            </nav>
        </aside>
    </div>
</div>
