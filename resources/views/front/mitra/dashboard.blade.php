<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mitra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
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

    <div class="header fixed-top">
        <div class="left">
            <a href="{{ url('/') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
        <h3>Dashboard Mitra</h3>
        <div class="right">
            <a href="#" class="icon logout-btn" onclick="event.preventDefault(); document.getElementById('mitraLogoutForm').submit();" title="Logout">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 3h5a1 1 0 011 1v9a1 1 0 01-1 1H6" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 8.5h6M5 6l-2.5 2.5L5 11" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <form id="mitraLogoutForm" action="{{ route('mitra.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="app-content style-2">
        <div class="tf-container">
            <div id="profil" class="mt-24">
                <div class="brankas-card" style="display:flex;border-radius:16px;overflow:hidden;background:#0d1f36;height:240px;">
                    <div style="flex:1;background:#0b1a2d;color:#fff;padding:16px;display:flex;flex-direction:column;justify-content:space-between;">
                        <div>
                            <div style="margin-top:6px;font-size:14px;">{{ $mitra->phone_wa ?? (auth()->user()->email ?? '') }}</div>
                            <div style="font-size:14px;">{{ $mitra->nama_lengkap ?? (auth()->user()->name ?? '') }}</div>
                            <div style="display:flex;gap:32px;margin-top:12px;">
                                <div>
                                    <div style="opacity:.8;font-size:12px;">Kode Mitra</div>
                                    <div style="font-size:14px;">{{ $mitra->kode_mitra ?? '-' }}</div>
                                </div>
                                <div>
                                    <div style="opacity:.8;font-size:12px;">Platform</div>
                                    <div style="font-size:14px;">{{ $mitra->platform ?? '-' }}</div>
                                </div>
                            </div>
                            <div style="margin-top:12px;">
                                <div style="opacity:.8;font-size:12px;">Status</div>
                                <div style="font-size:14px;">{{ ($mitra && $mitra->is_active) ? 'Aktif' : 'Nonaktif' }}</div>
                            </div>
                        </div>
                        <div style="margin-top:8px;border-top:1px solid rgba(255,255,255,.4);padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                            <div style="font-size:16px;font-weight:600;">Saldo Komisi Rp.2,000,000</div>
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
                            <span>MITRA</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-24">
                @if($mitra && ! $mitra->is_active)
                    <div class="alert alert-primary light alert-dismissible fade show mb-10">
                        Akun Anda sedang menunggu konfirmasi admin. Admin akan menghubungi Anda melalui WhatsApp yang Anda daftarkan ({{ $mitra->phone_wa ?? '-' }}). Pastikan nomor WhatsApp Anda sudah benar. Jika salah, <a href="#" id="toggleEditWa">edit di sini</a>.
                        <div id="editWaActions" class="mt-2" style="display:none;">
                            <button type="button" class="btn btn-primary btn-sm" id="btnEditWa">Edit Nomor WhatsApp</button>
                        </div>
                    </div>
                @else
                    <p class="text-muted">Konten dashboard mitra dapat dikembangkan sesuai kebutuhan (statistik, menu operasional, dsb.).</p>
                @endif

                <div id="komisi" class="card shadow-sm p-3 mt-16">
                    <h5 class="mb-2">Komisi Aktif</h5>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipe</th>
                                    <th>Komisi (%)</th>
                                    <th>Estimasi (IDR/gram)</th>
                                    <th>Berlaku</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($komisiList ?? []) as $k)
                                    @php $idrPerGram = (float)($hargaPerGram ?? 0); $estIdr = $idrPerGram * ((float)$k->komisi_persen) / 100.0; @endphp
                                    <tr>
                                        <td>{{ strtoupper($k->tipe_transaksi) }}</td>
                                        <td>{{ number_format((float)$k->komisi_persen, 2, ',', '.') }}</td>
                                        <td>{{ 'Rp ' . number_format($estIdr, 2, ',', '.') }}</td>
                                        <td>
                                            {{ optional($k->berlaku_mulai)->format('Y-m-d') ?? '-' }}
                                            @if($k->berlaku_sampai) s/d {{ optional($k->berlaku_sampai)->format('Y-m-d') }} @endif
                                        </td>
                                        <td>{{ $k->catatan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3">Belum ada komisi aktif.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('front.mitra.partials.menubar-footer', ['active' => 'dashboard'])

@include('front.mitra.partials.menubar-footer', ['active' => 'dashboard'])
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
    <script>
        (function($){
            $(function(){
                $('#toggleEditWa').on('click', function(e){
                    e.preventDefault();
                    $('#editWaActions').toggle();
                });
                $('#btnEditWa').on('click', function(){
                    alert('Fitur edit nomor WhatsApp akan tersedia. Silakan hubungi admin bila perlu segera.');
                });
            });
        })(jQuery);
    </script>
</body>
</html>