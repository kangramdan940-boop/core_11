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
    <title>Detail Ready || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.dashboard') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Detail Ready</h3>
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

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan Transaksi</h6>
            <div class="row g-3">
                <div class="col-md-4"><strong>Kode Transaksi</strong><br>{{ $ready->kode_trans }}</div>
                @php
                    $s = $ready->status;
                    $badge = 'text-bg-secondary';
                    if ($s === 'paid' || $s === 'completed') { $badge = 'text-bg-success'; }
                    elseif ($s === 'cancelled') { $badge = 'text-bg-danger'; }
                    elseif ($s === 'pending_payment') { $badge = 'text-bg-warning'; }
                    elseif ($s === 'shipped') { $badge = 'text-bg-primary'; }
                @endphp
                <div class="col-md-4"><strong>Status</strong><br><span class="badge rounded-pill {{ $badge }}">{{ strtoupper($s) }}</span></div>
                <div class="col-md-4"><strong>Total (IDR)</strong><br>{{ number_format((float)$ready->total_amount, 2, ',', '.') }}</div>
                <div class="col-md-4"><strong>Qty</strong><br>{{ $ready->qty }}</div>
                <div class="col-md-4"><strong>Harga Satuan</strong><br>{{ number_format((float)$ready->harga_jual_satuan, 2, ',', '.') }}</div>
                <div class="col-md-4"><strong>Item</strong><br>{{ optional($ready->readyStock)->kode_item ?? '-' }}</div>
            </div>
        </div>
    </div>

    @php
        $rawWa = optional($ready->agen)->phone_wa;
        $waPhone = preg_replace('/\D+/', '', (string)$rawWa);
        if (\Illuminate\Support\Str::startsWith($waPhone, '0')) {
            $waPhone = '62' . substr($waPhone, 1);
        }
        $userName = optional(Auth::user())->name;
        $waText = 'KODE READY: ' . $ready->kode_trans . ', Halo saya ' . ($userName ?? '-') . ', yang memiliki order, dan saya ingin bicara.';
        $waUrl = $waPhone ? ('https://wa.me/' . $waPhone . '?text=' . rawurlencode($waText)) : null;
        $hasProof = ($paymentLogs ?? collect())->contains(function($pl){ $p = json_decode($pl->request_payload, true); return !empty($p['proof_path']); });
    @endphp

    @if ($hasProof)
        <div class="alert alert-warning light">
            <div class="row align-items-center g-3">
                <div class="col-md-6 d-flex align-items-start gap-2">
                    <div class="fw-semibold">Agen (JE)</div>
                    <div>
                        <div>{{ optional($ready->agen)->name ?? '-' }}</div>
                        @if ($waUrl)
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="ms-1" title="Chat via WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#25D366"><path d="M12 0a12 12 0 0 0-10.6 17.9L0 24l6.2-1.6A12 12 0 1 0 12 0zm5.7 17.1c-.2.6-1.2 1.2-1.7 1.3c-.4.1-.9.2-1.5.1c-1.7-.2-3.1-1.1-4.3-2.3c-1.1-1.1-2-2.5-2.3-4.1c-.2-.8 0-1.4.3-1.9c.2-.3.5-.6.8-.6c.2 0 .4 0 .6.1c.2.1.4.5.5.8c.1.3.3.8.2 1c-.1.3-.2.5-.4.7c-.2.3-.5.6-.2 1.1c.3.6.7 1.2 1.2 1.7c.5.5 1 .9 1.6 1.2c.5.3.8.2 1.1-.1c.3-.3.6-.7.9-.9c.3-.2.6-.2 1-.1c.3.1.8.4 1 .6c.3.2.5.5.6.8c.1.3 0 .6-.1.8z"/></svg>
                            </a>
                        @endif
                        <div class="small">{{ optional($ready->agen)->phone_wa ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small fw-semibold">BANK BCA — Rek Tujuan:</div>
                    <div class="fw-bold text-decoration-underline" id="rekTujuanReady">{{ $ready->rekening_nomor ?? (optional($ready->agen)->rekening_nomor ?? '-') }}</div>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="copyRekBtnReady" title="Salin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4c-1.1 0-2 .9-2 2v12h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                    </button>
                    <span id="copyStatusReady" class="ms-2 text-success small" style="display:none;">Tersalin</span>
                </div>
            </div>
        </div>
        <script>
        (function(){
          var btn = document.getElementById('copyRekBtnReady');
          var el = document.getElementById('rekTujuanReady');
          var st = document.getElementById('copyStatusReady');
          function copy(){
            var txt = (el && el.textContent || '').trim();
            if(!txt || txt === '-') return;
            if (navigator.clipboard && navigator.clipboard.writeText) {
              navigator.clipboard.writeText(txt).then(function(){
                if(st){ st.style.display = 'inline'; setTimeout(function(){ st.style.display='none'; }, 1500); }
              });
            } else {
              var ta = document.createElement('textarea'); ta.value = txt; document.body.appendChild(ta); ta.select(); try{ document.execCommand('copy'); }catch(e){} document.body.removeChild(ta);
              if(st){ st.style.display = 'inline'; setTimeout(function(){ st.style.display='none'; }, 1500); }
            }
          }
          if(btn){ btn.addEventListener('click', copy); }
        })();
        </script>
    @else
        <div class="alert alert-warning light">
            <div class="row align-items-center g-3">
                <div class="col-md-6 d-flex align-items-start gap-2">
                    <div class="fw-semibold">Agen (JE)</div>
                    <div>
                        <div>{{ optional($ready->agen)->name ?? '-' }}</div>
                        <div class="text-muted small">Informasi kontak agen akan diberikan ketika sudah melakukan pembayaran.</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($ready->delivery_type === 'ship')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Alamat Pengiriman</h6>
                <div class="row g-3">
                    <div class="col-md-4"><strong>Nama</strong><br>{{ $ready->shipping_name ?? '-' }}</div>
                    <div class="col-md-4"><strong>HP</strong><br>{{ $ready->shipping_phone ?? '-' }}</div>
                    <div class="col-md-12"><strong>Alamat</strong><br>{{ $ready->shipping_address ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kota</strong><br>{{ $ready->shipping_city ?? '-' }}</div>
                    <div class="col-md-4"><strong>Provinsi</strong><br>{{ $ready->shipping_province ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kode Pos</strong><br>{{ $ready->shipping_postal_code ?? '-' }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Informasi Proses</h6>
            @if ($ready->status === 'pending_payment')
                <p class="mb-2">
                    Status: Menunggu pembayaran sejumlah
                    <strong>{{ number_format((float)$ready->total_amount, 2, ',', '.') }} IDR</strong>.
                </p>
                <p class="text-muted mb-2">Setelah dana diterima oleh agen, status akan berubah menjadi <strong>paid</strong> dan dilanjutkan ke pengiriman atau pengambilan.</p>
            @elseif ($ready->status === 'paid')
                <p class="mb-0">Pembayaran diterima. Menunggu proses pengiriman/pengambilan.</p>
            @elseif ($ready->status === 'shipped')
                <p class="mb-0">Barang dikirim. Mohon tunggu sampai diterima.</p>
            @elseif ($ready->status === 'completed')
                <p class="mb-0">Transaksi selesai. Terima kasih.</p>
            @elseif ($ready->status === 'cancelled')
                <p class="mb-0">Transaksi dibatalkan. Jika ada pengembalian dana, akan diproses terpisah.</p>
            @endif
        </div>
    </div>

    @php
        $pendingManual = ($paymentLogs ?? collect())
            ->filter(fn($pl) => ($pl->payment_method === 'manual_transfer') && ($pl->status === 'pending'))
            ->count();
    @endphp

    @if ($ready->status === 'pending_payment')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Konfirmasi Pembayaran Manual</h6>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-semibold mb-2">Terjadi kesalahan:</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @php $hasLogs = ($paymentLogs ?? collect())->count() > 0; $hasFailed = ($paymentLogs ?? collect())->where('status','failed')->count() > 0; @endphp
                @if ((!$hasLogs && ($pendingManual ?? 0) === 0) || $hasFailed)
                <form action="{{ route('customer.ready.confirm-payment', ['ready' => encrypt((string)$ready->id)]) }}" method="POST" enctype="multipart/form-data" class="mt-10">
                    @csrf
                    <div class="form-field form-2">
                        <div class="label h7">Nominal Transfer (IDR)</div>
                        <fieldset class="mt-12">
                            <input type="number" name="nominal_transfer" step="0.01" class="form-control @error('nominal_transfer') is-invalid @enderror" value="{{ old('nominal_transfer', number_format((float)$ready->total_amount, 0, '.', '')) }}">
                            @error('nominal_transfer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">Nama Pengirim</div>
                        <fieldset class="mt-12">
                            <input type="text" name="nama_pengirim" class="form-control @error('nama_pengirim') is-invalid @enderror" placeholder="Nama sesuai rekening" value="{{ old('nama_pengirim') }}">
                            @error('nama_pengirim')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                    <div class="form-field form-2 mt-24">
                        <div class="label h7">Bukti Transfer</div>
                        <fieldset class="mt-12">
                            <input type="file" name="bukti_transfer" accept="image/*" class="form-control @error('bukti_transfer') is-invalid @enderror">
                            @error('bukti_transfer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <div class="small text-muted mt-8">Unggah gambar struk/transfer. Format: JPG/PNG.</div>
                    </div>
                    <div class="mt-24">
                        <button type="submit" class="tf-btn primary">Kirim Konfirmasi</button>
                    </div>
                </form>
                @else
                    <div class="alert alert-info light mt-10">Konfirmasi pembayaran manual sudah dikirim atau payment log sudah tercatat. Anda tidak perlu mengirim struk lagi.</div>
                @endif
            </div>
        </div>
    @endif

    @if(($paymentLogs ?? collect())->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Payment Logs</h6>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Status</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Dibayar</th>
                                <th>Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentLogs as $pl)
                                @php $ps = $pl->status; $pbadge = 'text-bg-secondary'; if ($ps === 'paid') { $pbadge = 'text-bg-success'; } elseif ($ps === 'pending') { $pbadge = 'text-bg-warning'; } elseif ($ps === 'failed') { $pbadge = 'text-bg-danger'; } $payload = json_decode($pl->request_payload, true); $proof = $payload['proof_path'] ?? null; @endphp
                                <tr>
                                    <td>{{ $pl->kode_payment }}</td>
                                    <td><span class="badge rounded-pill {{ $pbadge }}">{{ strtoupper($ps) }}</span></td>
                                    <td>{{ number_format((float)$pl->amount, 2, ',', '.') }} {{ $pl->currency }}</td>
                                    <td>{{ $pl->payment_method ?? '-' }}</td>
                                    <td>{{ optional($pl->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td>
                                        @if ($proof)
                                            <img src="{{ asset($proof) }}" class="img-thumbnail js-proof" style="height:48px;cursor:pointer" alt="Bukti Transfer" data-src="{{ asset($proof) }}">
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">Belum ada payment log.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                      <div class="modal-body p-0">
                        <img id="proofModalImg" src="" alt="Bukti Transfer" class="w-100">
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                (function($){
                  $(function(){
                    $('.js-proof').on('click', function(){
                      var src = $(this).data('src');
                      $('#proofModalImg').attr('src', src);
                      var modal = new bootstrap.Modal(document.getElementById('proofModal'));
                      modal.show();
                    });
                  });
                })(jQuery);
                </script>
            </div>
        </div>
    @endif

    @if(($logs ?? collect())->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Log Status</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th>Deskripsi</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ strtoupper($log->status) }}</td>
                                    <td>{{ $log->description ?? '-' }}</td>
                                    <td>{{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
        </div>
    </div>
    @include('front.customer.partials.menubar-footer', ['active' => 'dashboard'])
    <script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/lazysize.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/jquery.nice-select.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/js/main.js')}}"></script>
</body>
</html>