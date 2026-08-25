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
    <title>Detail Buyback || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.buyback.index') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Detail Buyback</h3>
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
            <ul class="mb-0 small">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    @php
        $s = $trx->status;
        $badgeMap = [
            'pending_review' => 'text-bg-warning',
            'priced'         => 'text-bg-info',
            'approved'       => 'text-bg-primary',
            'paid'           => 'text-bg-success',
            'completed'      => 'text-bg-success',
            'rejected'       => 'text-bg-danger',
            'cancelled'      => 'text-bg-secondary',
        ];
        $labelMap = [
            'pending_review' => 'MENUNGGU VERIFIKASI',
            'priced'         => 'MENUNGGU PERSETUJUAN ANDA',
            'approved'       => 'DISETUJUI - MENUNGGU TRANSFER',
            'paid'           => 'DANA DITRANSFER',
            'completed'      => 'SELESAI',
            'rejected'       => 'DITOLAK',
            'cancelled'      => 'DIBATALKAN',
        ];
        $badge = $badgeMap[$s] ?? 'text-bg-secondary';
    @endphp

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan Buyback</h6>
            <div class="row g-3">
                <div class="col-md-6"><strong>Kode Transaksi</strong><br>{{ $trx->kode_trans }}</div>
                <div class="col-md-6"><strong>Status</strong><br><span class="badge rounded-pill {{ $badge }}">{{ $labelMap[$s] ?? strtoupper($s) }}</span></div>
                <div class="col-md-4"><strong>Brand</strong><br>{{ $trx->brand ?? '-' }}</div>
                <div class="col-md-4"><strong>Berat</strong><br>{{ number_format((float)$trx->berat_gram, 3, ',', '.') }} g</div>
                <div class="col-md-4"><strong>Jumlah</strong><br>{{ $trx->qty }} keping</div>
                <div class="col-md-4"><strong>Kondisi</strong><br>{{ $trx->kondisi ?? '-' }}</div>
                <div class="col-md-4"><strong>Sertifikat</strong><br>{{ $trx->ada_sertifikat ? 'Ada' : 'Tidak ada' }}</div>
                <div class="col-md-4"><strong>Metode Serah</strong><br>{{ $trx->metode_serah === 'kirim' ? 'Kirim ke Jajan Emas' : 'Datang ke lokasi' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Harga</h6>
            <div class="row g-3">
                <div class="col-md-6"><strong>Estimasi Awal / unit</strong><br>Rp {{ number_format((float)$trx->harga_buyback_estimasi, 0, ',', '.') }}</div>
                <div class="col-md-6">
                    <strong>Harga Final / unit</strong><br>
                    @if(!is_null($trx->harga_buyback_final))
                        Rp {{ number_format((float)$trx->harga_buyback_final, 0, ',', '.') }}
                    @else
                        <span class="text-muted">Belum ditetapkan</span>
                    @endif
                </div>
                <div class="col-md-12">
                    <strong>Total Diterima</strong><br>
                    <span class="fs-4 fw-bold text-success">Rp {{ number_format((float)$trx->total_amount, 0, ',', '.') }}</span>
                    @if($s === 'pending_review')
                        <div class="small text-muted">*Masih estimasi. Nilai final akan dikonfirmasi setelah verifikasi.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Rekening Tujuan Pencairan</h6>
            <div class="row g-3">
                <div class="col-md-4"><strong>Bank</strong><br>{{ $trx->bank_nama ?? '-' }}</div>
                <div class="col-md-4"><strong>No. Rekening</strong><br>{{ $trx->rekening_nomor ?? '-' }}</div>
                <div class="col-md-4"><strong>Atas Nama</strong><br>{{ $trx->rekening_atas_nama ?? '-' }}</div>
            </div>
        </div>
    </div>

    @if($trx->catatan_admin)
        <div class="alert alert-warning light">
            <strong>Catatan Admin:</strong> {{ $trx->catatan_admin }}
        </div>
    @endif

    {{-- Aksi customer sesuai status --}}
    @if($s === 'priced')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-2">Persetujuan Harga</h6>
                <p class="small text-muted mb-3">Admin telah menetapkan harga final <strong>Rp {{ number_format((float)$trx->total_amount, 0, ',', '.') }}</strong>. Setujui untuk melanjutkan pencairan.</p>
                <div class="row g-2">
                    <div class="col-7">
                        <form action="{{ route('customer.buyback.approve', ['buyback' => encrypt((string)$trx->id)]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                <i class="fa fa-check me-1"></i> Setujui Harga
                            </button>
                        </form>
                    </div>
                    <div class="col-5">
                        <form action="{{ route('customer.buyback.cancel', ['buyback' => encrypt((string)$trx->id)]) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan buyback ini?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-semibold">Batalkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif($s === 'pending_review')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <p class="mb-2">Pengajuan Anda sedang menunggu verifikasi emas oleh admin.</p>
                <form action="{{ route('customer.buyback.cancel', ['buyback' => encrypt((string)$trx->id)]) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan buyback ini?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Batalkan Pengajuan</button>
                </form>
            </div>
        </div>
    @elseif($s === 'paid' && $trx->bukti_transfer_path)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Bukti Transfer</h6>
                <img src="{{ asset($trx->bukti_transfer_path) }}" class="img-fluid rounded" style="max-height:320px" alt="Bukti Transfer">
            </div>
        </div>
    @endif

    @if(($logs ?? collect())->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Riwayat Status</h6>
                <table class="table table-sm table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Status</th><th>Deskripsi</th><th>Waktu</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            @php $isPaidWithProof = ($log->status === 'paid' && !empty($trx->bukti_transfer_path)); @endphp
                            <tr @if($isPaidWithProof) class="js-buyback-proof-row" style="cursor:pointer;" data-src="{{ asset($trx->bukti_transfer_path) }}" title="Klik untuk lihat bukti transfer" @endif>
                                <td>
                                    <span class="fw-semibold">{{ strtoupper($log->status) }}</span>
                                </td>
                                <td>{{ $log->description ?? '-' }}</td>
                                <td>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="text-end">
                                    @if($isPaidWithProof)
                                        <span class="badge rounded-pill text-bg-success js-buyback-proof-btn" style="cursor:pointer;" data-src="{{ asset($trx->bukti_transfer_path) }}">
                                            <i class="fa fa-image me-1"></i> Lihat Bukti
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
        </div>
    </div>

    {{-- Modal bukti transfer --}}
    <div class="modal fade" id="buybackProofModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header py-2">
            <h6 class="modal-title mb-0"><i class="fa fa-image me-1"></i> Bukti Transfer dari Admin</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body p-0 text-center" style="background:#f5f6f7;">
            <img id="buybackProofImg" src="" alt="Bukti Transfer" class="img-fluid" style="max-height:75vh;">
          </div>
          <div class="modal-footer py-2">
            <a id="buybackProofDownload" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Buka di tab baru</a>
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    @include('front.customer.partials.menubar-footer', ['active' => 'all-order'])
    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
    <script>
        (function($){
            function openProof(src){
                if (!src) return;
                $('#buybackProofImg').attr('src', src);
                $('#buybackProofDownload').attr('href', src);
                var modalEl = document.getElementById('buybackProofModal');
                if (window.bootstrap && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                } else {
                    $(modalEl).addClass('show').css('display', 'block');
                }
            }
            $(document).on('click', '.js-buyback-proof-btn', function(e){
                e.stopPropagation();
                openProof($(this).data('src'));
            });
            $(document).on('click', '.js-buyback-proof-row', function(){
                openProof($(this).data('src'));
            });
        })(jQuery);
    </script>
</body>
</html>
