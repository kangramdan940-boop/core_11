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
    <title>Detail PO || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.dashboard') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Detail PO</h3>
    </div>
    <div class="app-content style-3">
        <div class="tf-container">

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-3">Ringkasan PO</h6>
            <div class="row g-3">
                <div class="col-md-3"><strong>Kode PO</strong><br>{{ $po->kode_po }}</div>
                @php
                    $s = $po->status;
                    $badge = 'text-bg-secondary';
                    if ($s === 'paid' || $s === 'completed') { $badge = 'text-bg-success'; }
                    elseif ($s === 'cancelled') { $badge = 'text-bg-danger'; }
                    elseif ($s === 'pending_payment') { $badge = 'text-bg-warning'; }
                    elseif ($s === 'processing') { $badge = 'text-bg-info'; }
                    elseif ($s === 'ready_at_agen' || $s === 'shipped') { $badge = 'text-bg-primary'; }
                @endphp
                <div class="col-md-3"><strong>Status</strong><br><span class="badge rounded-pill {{ $badge }}">{{ strtoupper($s) }}</span></div>
                <div class="col-md-3"><strong>Harga</strong><br>{{ number_format((float)$po->harga_per_gram, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Total Gram</strong><br>{{ number_format((float)$po->total_gram, 0, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Biaya Jasa</strong><br>{{ number_format((float)$po->total_amount - $po->harga_per_gram , 0, ',', '.') }} g</div>
                <div class="col-md-3"><strong>Total Amount</strong><br>{{ number_format((float)$po->total_amount, 2, ',', '.') }}</div>
                <div class="col-md-3"><strong>Tipe Penerimaan</strong><br>{{ $po->delivery_type }}</div>
            </div>
        </div>
    </div>

    @if ($po->status === 'pending_payment')
    @php $pendingManual = ($paymentLogs ?? collect())
        ->filter(function($pl){ return ($pl->payment_method === 'manual_transfer') && ($pl->status === 'pending'); })
        ->count(); @endphp
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
            @php $hasLogs = ($paymentLogs ?? collect())->count() > 0; @endphp
            @if (!$hasLogs && ($pendingManual ?? 0) === 0 && !session('success'))
            <form action="{{ route('customer.po.confirm-payment', $po) }}" method="POST" enctype="multipart/form-data" class="mt-10">
                @csrf
                <div class="form-field form-2">
                    <div class="label h7">Nominal Transfer (IDR)</div>
                    <fieldset class="mt-12">
                        <input type="number" name="nominal_transfer" step="0.01" class="form-control @error('nominal_transfer') is-invalid @enderror" value="{{ old('nominal_transfer', number_format((float)$po->total_amount, 0, '.', '')) }}">
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

    @if ($po->status === 'shipped')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Data Pengiriman</h6>
                <div class="row g-3">
                    <div class="col-md-6"><strong>Nama</strong><br>{{ $po->shipping_name ?? '-' }}</div>
                    <div class="col-md-6"><strong>No. HP</strong><br>{{ $po->shipping_phone ?? '-' }}</div>
                    <div class="col-md-12"><strong>Alamat</strong><br>{{ $po->shipping_address ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kota</strong><br>{{ $po->shipping_city ?? '-' }}</div>
                    <div class="col-md-4"><strong>Provinsi</strong><br>{{ $po->shipping_province ?? '-' }}</div>
                    <div class="col-md-4"><strong>Kode Pos</strong><br>{{ $po->shipping_postal_code ?? '-' }}</div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-1">Portofolio Jasa Titip Emas</h6>
            <div class="small text-muted mb-3">Ringkasan jumlah PO yang telah kami tangani, dikelompokkan per status.</div>
            @php
                $stats = \App\Models\TransPo::selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total','status');
                $styles = [
                    'pending_payment' => ['label' => 'Menunggu Pembayaran', 'box' => 'bg-rgba-pink',   'text' => 'text-secondary-pink'],
                    'paid'            => ['label' => 'Dibayar',              'box' => 'bg-rgba-green-2','text' => 'text-secondary-green'],
                    'processing'      => ['label' => 'Diproses',             'box' => 'bg-rgba-violet', 'text' => 'text-secondary-violet'],
                    'ready_at_agen'   => ['label' => 'Siap di Agen',         'box' => 'bg-rgba-violet', 'text' => 'text-secondary-violet'],
                    'shipped'         => ['label' => 'Dikirim',              'box' => 'bg-rgba-violet', 'text' => 'text-secondary-violet'],
                    'completed'       => ['label' => 'Selesai',              'box' => 'bg-rgba-green-2','text' => 'text-secondary-green'],
                    'cancelled'       => ['label' => 'Dibatalkan',           'box' => 'bg-rgba-pink',   'text' => 'text-secondary-pink'],
                ];
            @endphp
            <div class="mt-22 wrap-count">
                @foreach($styles as $key => $meta)
                    <div class="count-item">
                        <div class="box-count {{ $meta['box'] }}">
                            <div class="h1 {{ $meta['text'] }}">{{ $stats[$key] ?? 0 }}</div>
                        </div>
                        <span class="text-dark body-2 fw-medium">{{ $meta['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @php $hasShippingLog = ($logs ?? collect())->where('status', 'shipped')->count() > 0; @endphp
    @if ($hasShippingLog)
    <div class="alert alert-secondary light alert-dismissible fade show mb-10">
        <div class="card-body">
            <h6 class="mb-3">Informasi Proses</h6>
            @if ($po->status === 'pending_payment')
                <p class="mb-2">
                    Status: Menunggu pembayaran dari Anda. Silakan lakukan pembayaran sejumlah
                    <strong>{{ number_format((float)$po->total_amount, 2, ',', '.') }} IDR</strong>.
                </p>
                <p class="text-muted mb-2">
                    Setelah dana diterima oleh agen, status akan diperbarui menjadi <strong>paid</strong> dan proses berlanjut ke <strong>processing</strong>.
                </p>
                <p class="mb-0">
                    Jika Anda sudah melakukan transfer, mohon tunggu konfirmasi dari agen. Anda dapat memantau perubahan status di halaman ini.
                </p>
            @elseif ($po->status === 'paid')
                <p class="mb-0">Pembayaran diterima. Menunggu agen memproses pembelian emas di brankas.</p>
            @elseif ($po->status === 'processing')
                <p class="mb-0">Sedang diproses oleh agen. Emas akan disiapkan.</p>
            @elseif ($po->status === 'ready_at_agen')
                <p class="mb-0">Emas sudah siap di agen. Menunggu pengiriman atau pengambilan sesuai pilihan Anda.</p>
            @elseif ($po->status === 'shipped')
                <p class="mb-0">Emas telah dikirim. Mohon tunggu sampai diterima.</p>
            @elseif ($po->status === 'completed')
                <p class="mb-0">Transaksi selesai. Terima kasih.</p>
            @elseif ($po->status === 'cancelled')
                <p class="mb-0">Transaksi dibatalkan. Jika ada pengembalian dana, akan diproses terpisah.</p>
            @endif
        </div>
    </div>
    @endif
    


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
                                        <img src="{{ asset('storage/' . $proof) }}" class="img-thumbnail js-proof" style="height:48px;cursor:pointer" alt="Bukti Transfer" data-src="{{ asset('storage/' . $proof) }}">
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

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Timeline</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $l)
                            <tr>
                                <td>{{ optional($l->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ strtoupper($l->status) }}</td>
                                <td>{{ $l->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">Belum ada log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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