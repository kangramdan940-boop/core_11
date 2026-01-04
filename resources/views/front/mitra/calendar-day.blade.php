<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Komisi Tanggal {{ $date }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('mitra.calendar') }}" class="icon">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
        <h3>Detail Komisi</h3>
    </div>

    <div class="app-content style-3">
        <div class="tf-container">
            <div style="margin-top:16px;">
                @php
                    $totalCount = $assignments->count();
                    $totalGram = (float) $assignments->sum('jumlah_gram');
                    $totalAmount = (float) $assignments->sum('komisi_amount');
                @endphp
                <div style="padding:16px;border-radius:16px;background:var(--white);box-shadow:0 1px 6px rgba(0,0,0,.06);">
                    <div style="font-weight:700;font-size:18px;margin-bottom:8px;">Tanggal {{ $date }}</div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:12px;font-family: monospace;">
                        <div class="badge bg-light text-dark">Transaksi: {{ $totalCount }}</div>
                        <div class="badge bg-light text-dark">Total Gram: {{ number_format($totalGram, 3, ',', '.') }} g</div>
                        <div class="badge bg-light text-dark">Total Komisi: Rp {{ number_format($totalAmount, 2, ',', '.') }}</div>
                    </div>

                    @forelse($assignments as $a)
                        <div style="border:1px solid rgba(0,0,0,.08);border-radius:12px;padding:12px;margin-bottom:12px;background:#fff;">
                            @php
                                $po = $a->po;
                                $qty = (int) ($po->qty ?? 1);
                                $hargaKeping = (float) ($po->harga_per_keping ?? 0);
                                $hargaPerGram = (float) ($po->harga_per_gram ?? 0);
                                $produk = optional($po->produk);
                                $gramasiNama = $produk->gramasi?->nama ?? '-';
                                $gramasiVal = (float) ($produk->gramasi?->gramasi ?? 0);
                                $jasaPerKeping = (float) ($produk->harga_jasa ?? 0);
                                $biayaJasaTotal = (float) $jasaPerKeping * $qty;
                                $subtotalProduk = (float) $hargaKeping * $qty;
                                $totalBayar = (float) ($po->total_amount ?? 0);
                                $feeUnik = max(0.0, $totalBayar - ($subtotalProduk + $biayaJasaTotal));
                                $pct = function(float $v, float $tot){ return $tot > 0 ? number_format($v / $tot * 100, 2, ',', '.') : '0.00'; };
                            @endphp
                            @php
                                $mList = ($mobilitiesByPo[$po->id] ?? collect());
                                $totalM = (float) $mList->sum('amount');
                                $feePool = (float) $biayaJasaTotal;
                                $netFee = (float) max(0.0, $feePool - $totalM);
                                $porsiGram = (float) (($po->total_gram ?? 0) > 0 ? ($a->jumlah_gram / $po->total_gram) : 0);
                                $basisKomisi = (float) number_format($netFee * $porsiGram, 2, '.', '');
                                $komisiFinal = (float) number_format($basisKomisi * ((float)$a->komisi_persen / 100), 2, '.', '');
                            @endphp

                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div style="font-size:12px;color:#0a7;">Komisi Mitra: {{ number_format((float)$a->komisi_persen, 2, ',', '.') }}% × (Fee Bersih × Porsi Gram) = Rp {{ number_format((float)$a->komisi_amount, 2, ',', '.') }}</div>
                            </div>

                            <div style="margin-top:8px;display:grid;grid-template-columns:1fr;gap:10px;">
                                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;font-family: monospace;">
                                    <div class="badge bg-light text-dark">Produk: {{ $gramasiNama }} {{ number_format($gramasiVal, 3, ',', '.') }} g</div>
                                    <div class="badge bg-light text-dark">Harga/Keping: Rp {{ number_format($hargaKeping, 2, ',', '.') }}</div>
                                    <div class="badge bg-light text-dark">Qty: {{ $qty }} pcs</div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Komponen</th>
                                                <th style="width:160px;">Nilai</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Total Dibayar Customer</td>
                                                <td>Rp {{ number_format($totalBayar, 2, ',', '.') }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Subtotal Produk</td>
                                                <td>Rp {{ number_format($subtotalProduk, 2, ',', '.') }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Subtotal Jasa (Fee Pool)</td>
                                                <td>Rp {{ number_format($feePool, 2, ',', '.') }}</td>
                                                <td>Jasa/Keping × Qty</td>
                                            </tr>
                                            <tr>
                                                <td>Total Biaya Mobilitas</td>
                                                <td>Rp {{ number_format($totalM, 2, ',', '.') }}</td>
                                                <td>Dari daftar mobilitas terkait</td>
                                            </tr>
                                            <tr class="table-warning">
                                                <td>Fee Bersih</td>
                                                <td>Rp {{ number_format($netFee, 2, ',', '.') }}</td>
                                                <td>Fee Pool − Total Mobilitas</td>
                                            </tr>
                                            <tr>
                                                <td>Porsi Gram</td>
                                                <td>{{ number_format($porsiGram * 100, 2, ',', '.') }}%</td>
                                                <td>Dialokasikan: {{ number_format((float)$a->jumlah_gram, 3, ',', '.') }} g dari {{ number_format((float)($po->total_gram ?? 0), 3, ',', '.') }} g</td>
                                            </tr>
                                            <tr class="table-info">
                                                <td>Basis Komisi</td>
                                                <td>Rp {{ number_format($basisKomisi, 2, ',', '.') }}</td>
                                                <td>Fee Bersih × Porsi Gram</td>
                                            </tr>
                                            <tr class="table-success">
                                                <td>Komisi Mitra</td>
                                                <td>Rp {{ number_format($komisiFinal, 2, ',', '.') }}</td>
                                                <td>{{ number_format((float)$a->komisi_persen, 2, ',', '.') }}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div style="font-size:12px;color:#666;margin-top:8px;">Catatan: {{ $a->catatan ?? '—' }}</div>

                            @php
                                $mList = ($mobilitiesByPo[$po->id] ?? collect());
                                $totalM = (float) $mList->sum('amount');
                            @endphp
                            <div style="margin-top:10px;">
                                <div style="font-weight:600;margin-bottom:6px;">Biaya Mobilitas Terkait (Total: Rp {{ number_format($totalM, 2, ',', '.') }})</div>
                                @if(($mList ?? collect())->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:110px;">Tanggal</th>
                                                    <th style="width:140px;">Kategori</th>
                                                    <th>Deskripsi</th>
                                                    <th style="width:140px;">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($mList as $m)
                                                    <tr>
                                                        <td>{{ optional($m->tanggal)->format('Y-m-d') ?? '-' }}</td>
                                                        <td>{{ $m->kategori ?? '-' }}</td>
                                                        <td>{{ $m->deskripsi ?? '-' }}</td>
                                                        <td>Rp {{ number_format((float)$m->amount, 2, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-muted" style="font-size:12px;">Tidak ada biaya mobilitas untuk PO ini.</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light">Tidak ada transaksi pada tanggal ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @include('front.mitra.partials.menubar-footer', ['active' => 'calendar'])

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
</body>
</html>