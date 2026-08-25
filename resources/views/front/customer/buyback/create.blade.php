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
    <title>Jual / Buyback Emas || Jajan Emas</title>
    <script>if (localStorage.toggled === "dark-theme") { document.documentElement.classList.add('dark-theme'); }</script>
    <style>
        .bb-ac-list {
            position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:1000;
            background:#fff; border:1px solid #e6e8eb; border-radius:10px;
            box-shadow:0 6px 20px rgba(0,0,0,.12);
            max-height:240px; overflow-y:auto; padding:4px;
        }
        .bb-ac-item {
            padding:10px 12px; border-radius:8px; font-size:14px; color:#1f2430;
            cursor:pointer; display:flex; align-items:center; gap:8px;
        }
        .bb-ac-item:hover, .bb-ac-item.active { background:#eef7f0; color:#1a7f37; }
        .bb-ac-item .bb-ac-dot { width:8px; height:8px; border-radius:50%; background:#d7b440; flex-shrink:0; }
        .bb-ac-empty { padding:12px; font-size:13px; color:#9aa0a6; text-align:center; }

        /* ===== Modal ringkasan (invoice style) ===== */
        .bb-modal-overlay {
            position:fixed; inset:0; z-index:2000; display:none;
            background:rgba(17,25,39,.55); backdrop-filter:blur(2px);
            align-items:flex-end; justify-content:center; padding:0;
        }
        .bb-modal-overlay.show { display:flex; }
        .bb-modal {
            background:#fff; width:100%; max-width:480px;
            border-radius:18px 18px 0 0; overflow:hidden;
            box-shadow:0 -8px 40px rgba(0,0,0,.25);
            display:flex; flex-direction:column; max-height:92vh;
            animation:bbSlideUp .28s ease;
        }
        @keyframes bbSlideUp { from{ transform:translateY(40px); opacity:.6;} to{ transform:translateY(0); opacity:1;} }
        @media (min-width:576px){
            .bb-modal-overlay { align-items:center; padding:20px; }
            .bb-modal { border-radius:18px; }
        }

        .bb-modal-head {
            display:flex; align-items:flex-start; justify-content:space-between;
            padding:18px 20px 16px; border-bottom:1px solid #eef0f2;
            background:linear-gradient(180deg,#fbfcfd,#fff);
        }
        .bb-modal-eyebrow { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#1a7f37; }
        .bb-modal-title { font-size:18px; font-weight:800; color:#1f2430; margin-top:2px; }
        .bb-modal-x { border:0; background:transparent; font-size:26px; line-height:1; color:#9aa0a6; cursor:pointer; padding:0 4px; }
        .bb-modal-x:hover { color:#1f2430; }

        .bb-modal-body { padding:16px 20px; overflow-y:auto; }

        .bb-inv-section { margin-bottom:16px; }
        .bb-inv-section-title {
            font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase;
            color:#9aa0a6; margin-bottom:8px; padding-bottom:6px; border-bottom:1px solid #f2f4f6;
        }
        .bb-inv-table { width:100%; border-collapse:collapse; }
        .bb-inv-table td { padding:6px 0; font-size:13.5px; vertical-align:top; }
        .bb-inv-table td:first-child { color:#8a9099; width:42%; }
        .bb-inv-table td:last-child { color:#1f2430; font-weight:600; text-align:right; }

        .bb-inv-note { font-size:13px; color:#4b515a; background:#f8f9fa; border-radius:10px; padding:10px 12px; white-space:pre-line; }

        .bb-inv-total {
            margin-top:6px; background:#f6faf7; border:1px solid #dcefe1;
            border-radius:12px; padding:14px 16px;
        }
        .bb-inv-total-row { display:flex; justify-content:space-between; align-items:center; font-size:13.5px; color:#5a616a; padding:3px 0; }
        .bb-inv-total-row span:last-child { font-weight:600; color:#1f2430; }
        .bb-inv-grand { border-top:1px dashed #cfe8d6; margin-top:8px; padding-top:10px; }
        .bb-inv-grand span:first-child { font-size:14px; font-weight:700; color:#1f2430; }
        .bb-inv-grand span:last-child { font-size:20px; font-weight:800; color:#1a7f37; }
        .bb-inv-disclaimer { font-size:11px; color:#9aa0a6; margin-top:10px; line-height:1.4; }

        .bb-modal-foot {
            display:flex; gap:10px; padding:14px 20px 18px; border-top:1px solid #eef0f2; background:#fff;
        }
        .bb-btn-ghost {
            flex:1; padding:12px; border-radius:10px; border:1px solid #d7dbe0;
            background:#fff; color:#5a616a; font-size:14px; font-weight:600; cursor:pointer;
        }
        .bb-btn-ghost:hover { background:#f5f6f7; }
        .bb-btn-solid {
            flex:1.4; padding:12px; border-radius:10px; border:0;
            background:#1a7f37; color:#fff; font-size:14px; font-weight:700; cursor:pointer;
            box-shadow:0 2px 8px rgba(26,127,55,.28);
        }
        .bb-btn-solid:hover { background:#166b2e; }
        .bb-btn-solid:disabled { opacity:.65; cursor:not-allowed; }
    </style>
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('customer.buyback.index') }}" class="icon back-btn">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
        </div>
        <h3>Jual / Buyback Emas</h3>
    </div>
    <div class="app-content style-3">
        <div class="tf-container" style="padding-top: 24px;">

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

    @php
        // Item terpilih awal: dari prefill (?ref) atau dari old() setelah gagal validasi
        $initialRef = old('ref', isset($prefill) ? encrypt((string) $prefill->id) : (request('ref') ?? ''));
        $initialLabel = '';
        $prefillEstimasi = 0;
        if (isset($prefill)) {
            $bnum = (float) preg_replace('/[^0-9.,]/', '', str_replace(',', '.', (string) $prefill->berat));
            $blabel = trim((string) $prefill->berat) !== '' ? trim((string) $prefill->berat) : ($bnum . ' gr');
            $prefillEstimasi = (int) $prefill->buyback;
            $initialLabel = trim((string) $prefill->brand) . ' - ' . $blabel . ' - Rp ' . number_format($prefillEstimasi, 0, ',', '.');
        } elseif ($initialRef) {
            $sel = ($buybackOptions ?? collect())->firstWhere('ref', $initialRef);
            if ($sel) { $initialLabel = $sel['label']; $prefillEstimasi = (int) $sel['buyback']; }
        }
    @endphp

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h6 mb-3">Ajukan Buyback Emas</h1>
            <p class="small text-muted">Pilih emas yang ingin Anda jual, lalu isi data rekening tujuan pencairan.</p>

            <form action="{{ route('customer.buyback.store') }}" method="POST" class="mt-10">
                @csrf
                <input type="hidden" name="ref" id="bbRef" value="{{ $initialRef }}">

                <div class="form-field form-2 mt-24">
                    <div class="label h7">Available Buyback</div>
                    <fieldset class="mt-12">
                        <div class="bb-ac" style="position:relative;">
                            <input type="text" id="bbSelect" autocomplete="off"
                                class="form-control @error('ref') is-invalid @enderror"
                                value="{{ $initialLabel }}" placeholder="Ketik / pilih: brand - berat - harga buyback" required readonly style="cursor:pointer;background:#fff;">
                            <div id="bbList" class="bb-ac-list" style="display:none;"></div>
                        </div>
                        <div class="small text-muted mt-1">Format: <strong>Brand - Berat - Harga Buyback</strong>. Berat &amp; estimasi harga otomatis mengikuti pilihan.</div>
                        @error('ref')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </fieldset>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-field form-2 mt-24">
                            <div class="label h7">Jumlah Keping</div>
                            <fieldset class="mt-12">
                                <input type="number" step="1" min="1" name="qty" id="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', 1) }}" required>
                                @error('qty')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-field form-2 mt-24">
                            <div class="label h7">Kondisi Emas</div>
                            <fieldset class="mt-12">
                                <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                                    <option value="">Pilih kondisi</option>
                                    <option value="Mulus / Segel" @selected(old('kondisi')==='Mulus / Segel')>Mulus / Segel</option>
                                    <option value="Baik" @selected(old('kondisi')==='Baik')>Baik</option>
                                    <option value="Ada Lecet" @selected(old('kondisi')==='Ada Lecet')>Ada Lecet</option>
                                    <option value="Tanpa Sertifikat" @selected(old('kondisi')==='Tanpa Sertifikat')>Tanpa Sertifikat</option>
                                </select>
                                @error('kondisi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="form-check mt-24">
                    <input class="form-check-input" type="checkbox" name="ada_sertifikat" id="ada_sertifikat" value="1" @checked(old('ada_sertifikat'))>
                    <label class="form-check-label" for="ada_sertifikat">Emas disertai sertifikat resmi</label>
                </div>

                <div class="form-field form-2 mt-24">
                    <div class="label h7">Metode Serah Emas</div>
                    <fieldset class="mt-12 d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_serah" id="serah_lokasi" value="datang_ke_lokasi" @checked(old('metode_serah','datang_ke_lokasi')==='datang_ke_lokasi')>
                            <label class="form-check-label" for="serah_lokasi">Datang ke lokasi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_serah" id="serah_kirim" value="kirim" @checked(old('metode_serah')==='kirim')>
                            <label class="form-check-label" for="serah_kirim">Kirim ke Jajan Emas</label>
                        </div>
                    </fieldset>
                </div>

                <hr class="mt-24">
                <div class="label h7 fw-bold">Rekening Tujuan Pencairan</div>
                <p class="small text-muted mb-0">Dana buyback akan ditransfer ke rekening ini setelah Anda menyetujui harga final.</p>

                <div class="form-field form-2 mt-24">
                    <div class="label h7">Nama Bank</div>
                    <fieldset class="mt-12">
                        <input type="text" name="bank_nama" class="form-control @error('bank_nama') is-invalid @enderror" value="{{ old('bank_nama') }}" placeholder="mis. BCA, Mandiri" required>
                        @error('bank_nama')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </fieldset>
                </div>
                <div class="form-field form-2 mt-24">
                    <div class="label h7">Nomor Rekening</div>
                    <fieldset class="mt-12">
                        <input type="text" name="rekening_nomor" class="form-control @error('rekening_nomor') is-invalid @enderror" value="{{ old('rekening_nomor') }}" required>
                        @error('rekening_nomor')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </fieldset>
                </div>
                <div class="form-field form-2 mt-24">
                    <div class="label h7">Atas Nama</div>
                    <fieldset class="mt-12">
                        <input type="text" name="rekening_atas_nama" class="form-control @error('rekening_atas_nama') is-invalid @enderror" value="{{ old('rekening_atas_nama', $customer->full_name ?? '') }}" required>
                        @error('rekening_atas_nama')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </fieldset>
                </div>

                <div class="form-field form-2 mt-24">
                    <div class="label h7">Catatan (opsional)</div>
                    <fieldset class="mt-12">
                        <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                    </fieldset>
                </div>

                <div class="mt-24" id="estimasiCard" style="{{ $prefillEstimasi > 0 ? '' : 'display:none;' }}">
                    <div style="background:#eef7f0;border:1px solid #cfe8d6;border-radius:14px;padding:18px 18px 16px;">
                        <div style="font-size:13px;color:#4b6b56;font-weight:600;letter-spacing:.02em;text-transform:uppercase;">Estimasi Diterima</div>
                        <div id="estimasiTotalDisplay" style="font-size:30px;line-height:1.15;font-weight:800;color:#1a7f37;margin-top:4px;">Rp {{ number_format($prefillEstimasi, 0, ',', '.') }}</div>
                        <div style="font-size:13px;color:#5a5a5a;margin-top:6px;">
                            <span id="estimasiHarga">Rp {{ number_format($prefillEstimasi, 0, ',', '.') }}</span>
                            <span style="color:#9aa0a6;">/ unit</span>
                            &times; <span id="estimasiQty">1</span> keping
                        </div>
                        <div style="border-top:1px dashed #cfe8d6;margin-top:12px;padding-top:10px;font-size:12px;color:#8a8f94;line-height:1.4;">
                            <i class="fa fa-info-circle" style="margin-right:4px;"></i>Ini estimasi, bukan harga final. Harga final ditetapkan setelah emas diverifikasi admin.
                        </div>
                    </div>
                </div>

                <div class="mt-24">
                    <button type="button" id="btnReview" class="tf-btn primary">Ajukan Buyback</button>
                </div>
            </form>

            {{-- ===== Modal Ringkasan (Resume) ===== --}}
            <div id="bbReviewOverlay" class="bb-modal-overlay">
                <div class="bb-modal" role="dialog" aria-modal="true" aria-labelledby="bbModalTitle">
                    <div class="bb-modal-head">
                        <div>
                            <div class="bb-modal-eyebrow">Konfirmasi Pengajuan</div>
                            <div class="bb-modal-title" id="bbModalTitle">Ringkasan Buyback Emas</div>
                        </div>
                        <button type="button" class="bb-modal-x" id="bbModalClose" aria-label="Tutup">&times;</button>
                    </div>

                    <div class="bb-modal-body">
                        <div class="bb-inv-section">
                            <div class="bb-inv-section-title">Detail Emas</div>
                            <table class="bb-inv-table">
                                <tr><td>Item</td><td id="rvItem">-</td></tr>
                                <tr><td>Jumlah</td><td id="rvQty">-</td></tr>
                                <tr><td>Kondisi</td><td id="rvKondisi">-</td></tr>
                                <tr><td>Sertifikat</td><td id="rvSertifikat">-</td></tr>
                                <tr><td>Metode Serah</td><td id="rvMetode">-</td></tr>
                            </table>
                        </div>

                        <div class="bb-inv-section">
                            <div class="bb-inv-section-title">Rekening Tujuan Pencairan</div>
                            <table class="bb-inv-table">
                                <tr><td>Bank</td><td id="rvBank">-</td></tr>
                                <tr><td>No. Rekening</td><td id="rvRek">-</td></tr>
                                <tr><td>Atas Nama</td><td id="rvAtasNama">-</td></tr>
                            </table>
                        </div>

                        <div class="bb-inv-section" id="rvCatatanWrap" style="display:none;">
                            <div class="bb-inv-section-title">Catatan</div>
                            <div class="bb-inv-note" id="rvCatatan">-</div>
                        </div>

                        <div class="bb-inv-total">
                            <div class="bb-inv-total-row">
                                <span>Harga Buyback / unit</span>
                                <span id="rvHarga">Rp 0</span>
                            </div>
                            <div class="bb-inv-total-row">
                                <span>Jumlah</span>
                                <span id="rvQtyLine">×1</span>
                            </div>
                            <div class="bb-inv-total-row bb-inv-grand">
                                <span>Estimasi Diterima</span>
                                <span id="rvTotal">Rp 0</span>
                            </div>
                            <div class="bb-inv-disclaimer">
                                *Nilai di atas adalah estimasi. Harga final ditetapkan setelah emas diverifikasi admin.
                            </div>
                        </div>
                    </div>

                    <div class="bb-modal-foot">
                        <button type="button" class="bb-btn-ghost" id="bbModalCancel">Periksa Lagi</button>
                        <button type="button" class="bb-btn-solid" id="bbConfirmSubmit">Konfirmasi &amp; Kirim</button>
                    </div>
                </div>
            </div>
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
            var options   = @json($buybackOptions ?? []);
            var basePrice = {{ (int) $prefillEstimasi }};

            var $select = $('#bbSelect');
            var $list   = $('#bbList');
            var $ref    = $('#bbRef');

            function formatRupiah(a){ return 'Rp ' + new Intl.NumberFormat('id-ID').format(a); }

            function updateTotal(){
                var qty = parseInt($('#qty').val(), 10) || 1;
                if (qty < 1) qty = 1;
                var total = basePrice * qty;
                if (basePrice > 0) {
                    $('#estimasiCard').show();
                    $('#estimasiTotalDisplay').text(formatRupiah(total));
                    $('#estimasiHarga').text(formatRupiah(basePrice));
                    $('#estimasiQty').text(qty);
                } else {
                    $('#estimasiCard').hide();
                }
            }

            function esc(s){ return $('<div>').text(s == null ? '' : String(s)).html(); }

            function render(items){
                if (!items.length){
                    $list.html('<div class="bb-ac-empty">Data buyback tidak ditemukan</div>').show();
                    return;
                }
                var html = '';
                items.forEach(function(o){
                    html += '<div class="bb-ac-item" data-ref="' + esc(o.ref) + '" data-buyback="' + (o.buyback||0) + '">'
                          + '<span class="bb-ac-dot"></span>' + esc(o.label) + '</div>';
                });
                $list.html(html).show();
            }

            function filtered(){
                var q = ($select.data('q') || '').toLowerCase().trim();
                if (!q) return options;
                return options.filter(function(o){ return (o.label||'').toLowerCase().indexOf(q) !== -1; });
            }

            // Buka daftar saat diklik
            $select.on('click focus', function(){
                $select.data('q', '');
                render(options);
            });

            // Pilih item
            $list.on('mousedown', '.bb-ac-item', function(e){
                e.preventDefault();
                var ref = $(this).data('ref');
                var opt = options.filter(function(o){ return o.ref === ref; })[0];
                if (opt){
                    $select.val(opt.label);
                    $ref.val(opt.ref);
                    basePrice = parseInt(opt.buyback, 10) || 0;
                    updateTotal();
                }
                $list.hide();
            });

            $(document).on('click', function(e){
                if (!$(e.target).closest('.bb-ac').length) $list.hide();
            });

            $('#qty').on('input change', updateTotal);
            updateTotal();

            // ===== Modal Ringkasan =====
            var $form    = $('#btnReview').closest('form');
            var $overlay = $('#bbReviewOverlay');

            function openReview(){
                // Validasi: item terpilih
                if (!$ref.val()) {
                    alert('Silakan pilih item buyback dari daftar "Available Buyback" terlebih dahulu.');
                    $select.trigger('click');
                    return;
                }
                // Validasi field wajib rekening
                var required = [
                    { sel: '[name=bank_nama]',          label: 'Nama Bank' },
                    { sel: '[name=rekening_nomor]',     label: 'Nomor Rekening' },
                    { sel: '[name=rekening_atas_nama]', label: 'Atas Nama' },
                ];
                for (var i=0; i<required.length; i++){
                    var $f = $form.find(required[i].sel);
                    if (!$.trim($f.val())) {
                        alert('Mohon lengkapi: ' + required[i].label);
                        $f.focus();
                        return;
                    }
                }

                var qty   = parseInt($('#qty').val(), 10) || 1;
                var metode = $form.find('[name=metode_serah]:checked').val() === 'kirim' ? 'Kirim ke Jajan Emas' : 'Datang ke lokasi';
                var kondisi = $form.find('[name=kondisi]').val() || '-';
                var sertif  = $form.find('[name=ada_sertifikat]').is(':checked') ? 'Ada' : 'Tidak ada';
                var catatan = $.trim($form.find('[name=catatan]').val() || '');
                var total   = basePrice * qty;

                $('#rvItem').text($select.val() || '-');
                $('#rvQty').text(qty + ' keping');
                $('#rvKondisi').text(kondisi);
                $('#rvSertifikat').text(sertif);
                $('#rvMetode').text(metode);
                $('#rvBank').text($form.find('[name=bank_nama]').val());
                $('#rvRek').text($form.find('[name=rekening_nomor]').val());
                $('#rvAtasNama').text($form.find('[name=rekening_atas_nama]').val());
                $('#rvHarga').text(formatRupiah(basePrice));
                $('#rvQtyLine').text('\u00d7' + qty);
                $('#rvTotal').text(formatRupiah(total));

                if (catatan) { $('#rvCatatan').text(catatan); $('#rvCatatanWrap').show(); }
                else { $('#rvCatatanWrap').hide(); }

                $overlay.addClass('show');
                document.body.style.overflow = 'hidden';
            }

            function closeReview(){
                $overlay.removeClass('show');
                document.body.style.overflow = '';
            }

            $('#btnReview').on('click', openReview);
            $('#bbModalClose, #bbModalCancel').on('click', closeReview);
            $overlay.on('click', function(e){ if (e.target === this) closeReview(); });
            $(document).on('keydown', function(e){ if (e.key === 'Escape' && $overlay.hasClass('show')) closeReview(); });

            $('#bbConfirmSubmit').on('click', function(){
                var $b = $(this);
                $b.prop('disabled', true).text('Mengirim...');
                $form[0].submit();
            });
        })(jQuery);
    </script>
</body>
</html>
