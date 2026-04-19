<!DOCTYPE html>
<html lang="en">

<head>

   <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Jajan Emas - Beli Emas Online Terpercaya | Harga Emas Hari Ini</title>

<meta name="description" content="Jajan Emas adalah toko emas terpercaya di Bekasi yang melayani pembelian emas Antam, UBS, Galeri24, BSI, dan Harta Dinata. Harga kompetitif, transparan, tersedia ready stock & pre-order.">

<meta name="keywords" content="jual emas, beli emas online, harga emas hari ini, emas antam, emas ubs, galeri24, buyback emas, toko emas bekasi, investasi emas">

<meta name="author" content="Jajan Emas">

<meta name="robots" content="index, follow">

<link rel="canonical" href="https://jajanemas.com/">

<!-- Open Graph (Facebook, WhatsApp, dll) -->
<meta property="og:title" content="Jajan Emas - Beli Emas Online Terpercaya">
<meta property="og:description" content="Beli emas dengan mudah, aman, dan transparan. Tersedia emas Antam, UBS, Galeri24, dan lainnya dengan harga kompetitif.">
<meta property="og:image" content="https://jajanemas.com/assets/images/og-image.jpg">
<meta property="og:url" content="https://jajanemas.com/">
<meta property="og:type" content="website">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Jajan Emas - Beli Emas Online Terpercaya">
<meta name="twitter:description" content="Investasi emas jadi lebih mudah dengan harga transparan dan layanan terbaik.">
<meta name="twitter:image" content="https://jajanemas.com/assets/images/og-image.jpg">

<!-- Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template_home_front/assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('template_home_front/assets/css/font-awesome.css') }}">

    <link rel="stylesheet" href="{{ asset('template_home_front/assets/css/templatemo-lava.css') }}">

    <link rel="stylesheet" href="{{ asset('template_home_front/assets/css/owl-carousel.css') }}">

</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/images/logo/logo-logotype.png') }}" alt="Jajan Emas" class="logo-img">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#welcome" class="menu-item">Home</a></li>
                            <li class="scroll-to-section"><a href="#about" class="menu-item">About</a></li>
                            <li class="scroll-to-section"><a href="#testimonials" class="menu-item">Testimonials</a>
                            </li>
                            <li class="scroll-to-section"><a href="#contact-us" class="menu-item">Contact Us</a></li>
                            <li class="scroll-to-section"><a href="{{ route('customer.login') }}" class="menu-item">Login</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->


    <!-- ***** Welcome Area Start ***** -->
    <div class="welcome-area" id="welcome">

        <!-- ***** Header Text Start ***** -->
        <div class="header-text">
            <div class="container">
                <div class="row">
                    <div class="left-text col-lg-6 col-md-12 col-sm-12 col-xs-12"
                        data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                        <h1>Selamat datang<br> di <em>Jajan Emas</em></h1>
                        <p>Solusi mudah dan terpercaya untuk membeli emas berkualitas dengan harga terbaik</p>

                        <div class="hero-price-card">
                            <div class="hero-price-card-top">
                                <div class="hero-price-card-heading">
                                    <div class="hero-price-card-icon" aria-hidden="true">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    <div class="hero-price-card-heading-text">
                                        <div class="hero-price-card-title">Harga Emas Hari Ini</div>
                                        <div class="hero-price-card-subtitle">Update terakhir</div>
                                    </div>
                                </div>
                                <div class="hero-live-badge">
                                    <span class="hero-live-dot" aria-hidden="true"></span>
                                    Live
                                </div>
                            </div>

                            <div class="hero-price-card-price">
                                <span class="hero-price-currency">Rp</span>
                                <span class="hero-price-value">
                                    @if(!empty($goldPrice?->buy_price))
                                        {{ number_format((float) $goldPrice->buy_price, 0, ',', '.') }}
                                    @else
                                       -
                                    @endif
                                </span>
                                <span class="hero-price-unit">/ gram</span>
                            </div>

                            <div class="hero-price-card-meta">
                               
                                <div class="hero-price-change">
                                    Harga Buyback:
                                    <span class="hero-price-change-note">@if(!empty($goldPrice?->buyback_price))
                                            Rp {{ number_format((float) $goldPrice->buyback_price, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="hero-price-card-footer">
                                <div class="hero-price-time">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    @if(!empty($goldPrice?->price_date))
                                        {{ \Illuminate\Support\Carbon::parse($goldPrice->price_date)->format('d M Y') }}
                                        @if(!empty($goldPrice?->last_updated))
                                            &bull; {{ \Illuminate\Support\Carbon::parse($goldPrice->last_updated)->format('H:i') }} WIB
                                        @endif
                                    @else
                                        {{ date('d M Y') }} &bull; {{ date('H:i') }} WIB
                                    @endif
                                </div>
                                <div class="hero-price-source">Price: {{ $goldPrice->source ?? 'Jajan Emas' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block"></div>
                </div>
            </div>
        </div>
        <!-- ***** Header Text End ***** -->


    </div>
    <!-- ***** Welcome Area End ***** -->

    <!-- ***** Benefits Section Start ***** -->
    <section class="hero-benefits-section">
        <div class="hero-benefits-wrap">
            <div class="hero-benefits">
                @php
                    $defaultIcons = ['fa fa-certificate', 'fa fa-star', 'fa fa-diamond', 'fa fa-bank'];
                @endphp

                @forelse(($floatingPrices ?? []) as $idx => $fp)
                    @php
                        $iconClass = $fp->icon ?: ($defaultIcons[$idx] ?? 'fa fa-cubes');
                        $brand = $fp->brand ?: '-';
                        $harga = (int) ($fp->harga ?? 0);
                        $buyback = (int) ($fp->buyback ?? 0);
                    @endphp
                    <div class="hero-benefit">
                        <div class="hero-benefit-icon" aria-hidden="true"><i class="{{ $iconClass }}"></i></div>
                        <div class="hero-benefit-text">
                            <div class="hero-benefit-title">{{ $brand }}</div>
                            <div class="hero-benefit-price">Rp {{ number_format($harga, 0, ',', '.') }}<span>/gr</span></div>
                            <div class="hero-benefit-buyback"><i class="fa fa-exchange"></i> Buyback: Rp {{ number_format($buyback, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="hero-benefit">
                        <div class="hero-benefit-icon" aria-hidden="true"><i class="fa fa-cubes"></i></div>
                        <div class="hero-benefit-text">
                            <div class="hero-benefit-title">-</div>
                            <div class="hero-benefit-price">Rp -<span>/gr</span></div>
                            <div class="hero-benefit-buyback"><i class="fa fa-exchange"></i> Buyback: Rp -</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- ***** Benefits Section End ***** -->

    <!-- ***** Features Big Item Start ***** -->
    <section class="section" id="about">
        <div class="container">
            <div class="row align-items-stretch">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <i class="fa fa-cubes" aria-hidden="true" style="font-size:48px; margin-bottom: 20px; color:#f1c40f;"></i>
                            <h4>Emas Ready Hari Ini</h4>
                            <p>Tersedia emas siap kirim dengan harga terbaik. Cocok untuk Anda yang ingin langsung memiliki emas tanpa menunggu.</p>
                            <a href="#" class="main-button" onclick="openModal('modal-etalase'); return false;">
                                Lihat Etalase
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <i class="fa fa-clock-o" aria-hidden="true" style="font-size:48px; margin-bottom: 20px; color:#3498db;"></i>
                            <h4>Emas Pre Order</h4>
                            <p>Dapatkan harga lebih kompetitif melalui sistem pre-order dengan estimasi 4–45 hari, aman dan terpercaya.</p>
                            <a href="#" class="main-button" onclick="openModal('modal-preorder'); return false;">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <i class="fa fa-exchange" aria-hidden="true" style="font-size:48px; margin-bottom: 20px; color:#2ecc71;"></i>
                            <h4>Jual / Buyback Emas</h4>
                            <p>Jual kembali emas Anda dengan proses mudah dan harga kompetitif, bahkan di atas harga dasar Antam.</p>
                            <a href="#" class="main-button" onclick="openModal('modal-buyback'); return false;">
                                Cek Harga Buyback
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Big Item End ***** -->

    <!-- ===== MODALS ===== -->
    <div id="modal-etalase" class="jj-modal-overlay" onclick="closeModalOverlay(event, 'modal-etalase')">
        <div class="jj-modal">
            <div class="jj-modal-header">
                <h4><i class="fa fa-cubes"></i> Etalase Emas Ready</h4>
                <button class="jj-modal-close" onclick="closeModal('modal-etalase')">&times;</button>
            </div>
            <div class="jj-modal-body">
                <p class="jj-modal-desc">Stok emas siap kirim hari ini. Harga dapat berubah sewaktu-waktu mengikuti harga pasar.</p>
                <div class="jj-table-wrap">
                    <table class="jj-table">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Berat</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($etalaseReady ?? []) as $row)
                                @php
                                    $brand = (string) ($row->brand ?? '-');
                                    $brandLower = strtolower($brand);
                                    $badgeClass = str_contains($brandLower, 'antam') ? 'jj-badge-gold' : (str_contains($brandLower, 'bsi') ? 'jj-badge-blue' : 'jj-badge-silver');
                                    $harga = (int) ($row->harga ?? 0);
                                    $stok = (string) ($row->stok ?? '-');
                                    $status = (string) ($row->status ?? '-');
                                    $statusLower = strtolower($status);
                                    $statusClass = (str_contains($statusLower, 'habis') || str_contains($statusLower, 'kosong')) ? 'jj-status-empty' : 'jj-status-ready';
                                @endphp
                                <tr>
                                    <td><span class="jj-badge {{ $badgeClass }}">{{ $brand }}</span></td>
                                    <td>{{ $row->berat ?? '-' }}</td>
                                    <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                    <td>{{ $stok }}</td>
                                    <td><span class="jj-status {{ $statusClass }}">{{ $status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data belum tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="jj-modal-footer">
                <small><i class="fa fa-info-circle"></i> Harga belum termasuk ongkos kirim &bull; Pembayaran di muka 100%</small>
            </div>
        </div>
    </div>

    <div id="modal-preorder" class="jj-modal-overlay" onclick="closeModalOverlay(event, 'modal-preorder')">
        <div class="jj-modal">
            <div class="jj-modal-header">
                <h4><i class="fa fa-clock-o"></i> Daftar Harga Pre Order</h4>
                <button class="jj-modal-close" onclick="closeModal('modal-preorder')">&times;</button>
            </div>
            <div class="jj-modal-body">
                <p class="jj-modal-desc">Harga pre-order lebih kompetitif. Estimasi pengiriman 4–45 hari kerja setelah pembayaran dikonfirmasi.</p>
                <div class="jj-table-wrap">
                    <table class="jj-table">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Berat</th>
                                <th>Harga PO</th>
                                <th>Estimasi</th>
                                <th>Min. Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($etalasePreorder ?? []) as $row)
                                @php
                                    $brand = (string) ($row->brand ?? '-');
                                    $brandLower = strtolower($brand);
                                    $badgeClass = str_contains($brandLower, 'antam') ? 'jj-badge-gold' : (str_contains($brandLower, 'bsi') ? 'jj-badge-blue' : 'jj-badge-silver');
                                    $harga = (int) ($row->harga ?? 0);
                                    $estimasi = (string) ($row->stok ?? '-');
                                    $minOrder = (string) ($row->status ?? '-');
                                @endphp
                                <tr>
                                    <td><span class="jj-badge {{ $badgeClass }}">{{ $brand }}</span></td>
                                    <td>{{ $row->berat ?? '-' }}</td>
                                    <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                    <td>{{ $estimasi }}</td>
                                    <td>{{ $minOrder }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data belum tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="jj-modal-footer">
                <small><i class="fa fa-info-circle"></i> Harga belum termasuk ongkos kirim &bull; Pembayaran di muka 100%</small>
            </div>
        </div>
    </div>

    <div id="modal-buyback" class="jj-modal-overlay" onclick="closeModalOverlay(event, 'modal-buyback')">
        <div class="jj-modal">
            <div class="jj-modal-header">
                <h4><i class="fa fa-exchange"></i> Harga Buyback Hari Ini</h4>
                <button class="jj-modal-close" onclick="closeModal('modal-buyback')">&times;</button>
            </div>
            <div class="jj-modal-body">
                <p class="jj-modal-desc">Jual emas Anda ke kami dengan harga kompetitif. Proses cepat, pembayaran langsung ditransfer.</p>
                <div class="jj-table-wrap">
                    <table class="jj-table">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Berat</th>
                                <th>Harga Buyback</th>
                                <th>Syarat</th>
                                <th>Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($etalaseBuyback ?? []) as $row)
                                @php
                                    $brand = (string) ($row->brand ?? '-');
                                    $brandLower = strtolower($brand);
                                    $badgeClass = str_contains($brandLower, 'antam') ? 'jj-badge-gold' : (str_contains($brandLower, 'bsi') ? 'jj-badge-blue' : 'jj-badge-silver');
                                    $hargaBuyback = (int) ($row->buyback ?? 0);
                                    $syarat = (string) ($row->stok ?? '-');
                                    $proses = (string) ($row->status ?? '-');
                                @endphp
                                <tr>
                                    <td><span class="jj-badge {{ $badgeClass }}">{{ $brand }}</span></td>
                                    <td>{{ $row->berat ?? '-' }}</td>
                                    <td>Rp {{ number_format($hargaBuyback, 0, ',', '.') }}</td>
                                    <td>{{ $syarat }}</td>
                                    <td>{{ $proses }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data belum tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="jj-modal-footer">
                <small><i class="fa fa-info-circle"></i> Harga buyback update setiap hari &bull; Kondisi emas harus mulus dan bersertifikat</small>
            </div>
        </div>
    </div>
    <!-- ===== END MODALS ===== -->


    <div class="left-image-decor"></div>

    <!-- ***** Features Big Item Start ***** -->
    <section class="section" id="promotion">
        <div class="container">
            <div class="row">
                <div class="left-image col-lg-5 col-md-12 col-sm-12 mobile-bottom-fix-big"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <img src="{{ asset('template_home_front/assets/images/left-image.png') }}" class="rounded img-fluid d-block mx-auto" alt="App">
                </div>
                <div class="right-text offset-lg-1 col-lg-6 col-md-12 col-sm-12 mobile-bottom-fix">
                    <ul>
                        <li data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                                <i class="fa fa-bar-chart" aria-hidden="true" style="font-size:28px; width:28px; text-align:center;"></i>
                                <div class="text">
                                    <h4>Harga Transparan & Kompetitif</h4>
                                    <p>Dapatkan harga emas terbaik yang selalu diperbarui, tanpa biaya tersembunyi.</p>
                                </div>
                            </li>
                            <li data-scroll-reveal="enter right move 30px over 0.6s after 0.5s">
                                <i class="fa fa-shield" aria-hidden="true" style="font-size:28px; width:28px; text-align:center;"></i>
                                <div class="text">
                                    <h4>Aman & Terpercaya</h4>
                                    <p>Telah melayani ribuan pelanggan dengan sistem transaksi yang aman dan profesional.</p>
                                </div>
                            </li>
                            <li data-scroll-reveal="enter right move 30px over 0.6s after 0.6s">
                                <i class="fa fa-random" aria-hidden="true" style="font-size:28px; width:28px; text-align:center;"></i>
                                <div class="text">
                                    <h4>Fleksibel & Mudah</h4>
                                    <p>Beli emas secara online atau offline, tersedia ready stock dan pre-order sesuai kebutuhan Anda.</p>
                                </div>
                            </li>
                        </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Big Item End ***** -->

    <div class="right-image-decor"></div>

    <!-- ***** Testimonials Starts ***** -->
    <section class="section" id="testimonials">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="center-heading">
                        <h2>Apa Kata Pelanggan  <em>Jajan Emas</em></h2>
                        <p>Ribuan pelanggan telah mempercayakan pembelian emasnya di Jajan Emas dengan proses yang aman, mudah, dan transparan.</p>
                    </div>
                </div>
                <div class="col-lg-10 col-md-12 col-sm-12 mobile-bottom-fix-big"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <div class="owl-carousel owl-theme">
                        <div class="item service-item">
                            <div class="testimonial-content">
                                <ul class="stars">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                </ul>
                                <h4>Rina Oktaviani</h4>
                                <p>"Pertama kali beli emas online agak ragu, tapi di Jajan Emas ternyata prosesnya cepat dan jelas. Harganya juga lebih murah dari toko lain."</p>
                                <span>Ibu Rumah Tangga</span>
                            </div>
                        </div>
                        <div class="item service-item">
                            <div class="testimonial-content">
                                <ul class="stars">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                </ul>
                                <h4>Dewi Lestari</h4>
                                <p>"Saya ambil pre-order dan hasilnya sesuai janji. Pelayanannya ramah dan transparan, jadi makin percaya untuk investasi emas di sini."</p>
                                <span>Karyawan Swasta</span>
                            </div>
                        </div>
                        <div class="item service-item">
                            <div class="testimonial-content">
                                <ul class="stars">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                </ul>
                                <h4>Siti Rahmawati</h4>
                                <p>"Buyback di Jajan Emas harganya bagus banget, bahkan lebih tinggi dari yang saya kira. Prosesnya juga cepat dan tidak ribet."</p>
                                <span>Wirausaha</span>
                            </div>
                        </div>
                        <div class="item service-item">
                            <div class="testimonial-content">
                                <ul class="stars">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                </ul>
                                <h4>Putri Maharani</h4>
                                <p>"Suka banget karena bisa beli emas dari rumah. Pilihannya lengkap dan harganya transparan, jadi nggak khawatir."</p>
                                <span>Freelancer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ***** Footer Start ***** -->
    <footer id="contact-us">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <!-- ***** Contact Form Start ***** -->
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="coming-soon-app">
                            <div class="coming-soon-icon" aria-hidden="true">
                                <i class="fa fa-mobile"></i>
                            </div>
                            <h3>Segera Hadir di <em>Genggaman Kamu</em></h3>
                            <p>Aplikasi Jajan Emas akan segera tersedia di Play Store &amp; App Store. Investasi emas jadi lebih mudah, kapan saja dan di mana saja.</p>
                            <div class="coming-soon-badges">
                                <a href="#" class="store-badge store-badge-play">
                                    <i class="fa fa-android"></i>
                                    <div class="store-badge-text">
                                        <span>Segera di</span>
                                        <strong>Google Play</strong>
                                    </div>
                                </a>
                                <a href="#" class="store-badge store-badge-apple">
                                    <i class="fa fa-apple"></i>
                                    <div class="store-badge-text">
                                        <span>Segera di</span>
                                        <strong>App Store</strong>
                                    </div>
                                </a>
                            </div>
                            <div class="coming-soon-note">
                                <i class="fa fa-bell"></i> Notifikasi akan dikirim saat aplikasi siap diluncurkan
                            </div>
                        </div>
                    </div>
                    <!-- ***** Contact Form End ***** -->
                    <div class="right-content col-lg-6 col-md-12 col-sm-12">
                        <h2>Tentang  <em>Jajan Emas</em></h2>
                        <p>Jajan Emas adalah toko emas dari PT Sinergi Aurum Mulya yang telah melayani lebih dari 6.632 pelanggan melalui website kami.

Kami menyediakan emas berkualitas dari Antam, Galeri24, Harta Dinata, BSI, dan UBS dengan harga yang kompetitif. Melayani pembelian online & offline, baik ready stock maupun pre-order (4–45 hari).

Berlokasi di Bekasi, kami juga melayani buyback dengan harga menarik, serta selalu mengedepankan transparansi dan pelayanan terbaik untuk setiap pelanggan.</p>
                        <ul class="social">
                            <li><a href="https://www.tiktok.com/@kang_ramdan_kang_ramdan" target="_blank" rel="noopener"><i class="fa fa-music"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="sub-footer">
                        <p>Copyright &copy; 2020 Lava Landing Page

                        | Designed by <a rel="nofollow" href="https://templatemo.com">TemplateMo</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="{{ asset('template_home_front/assets/js/jquery-2.1.0.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('template_home_front/assets/js/popper.js') }}"></script>
    <script src="{{ asset('template_home_front/assets/js/bootstrap.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('template_home_front/assets/js/owl-carousel.js') }}"></script>
    <script src="{{ asset('template_home_front/assets/js/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('template_home_front/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('template_home_front/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('template_home_front/assets/js/imgfix.min.js') }}"></script>

    <!-- Global Init -->
    <script src="{{ asset('template_home_front/assets/js/custom.js') }}"></script>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
            document.body.style.overflow = '';
        }
        function closeModalOverlay(e, id) {
            if (e.target === document.getElementById(id)) closeModal(id);
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.jj-modal-overlay.active').forEach(function(el) {
                    el.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        });
    </script>

</body>
</html>