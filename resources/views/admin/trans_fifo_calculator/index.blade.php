@extends('layouts.admin.master')

@section('title', 'Kalkulator FIFO - Admin')
@section('sub-title', 'Transaksi')
@section('breadcrumbExtra', 'Kalkulator FIFO')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.fifo-calculator'))

@section('content')
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.trans.fifo-calculator') }}" class="row g-3">
                <div class="col-12 col-md-12">
                    <label class="form-label mb-1">Pilih Faktur</label>
                    @php
                        $currentFakturs = (array) ($fakturs ?? [])
                    @endphp
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-12 col-md">
                            <input type="text" id="fakturSearchInput" class="form-control form-control-sm" placeholder="Cari nomor faktur...">
                        </div>
                        <div class="col-12 col-md-auto">
                            <select id="fakturDistribFilter" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="ya">Sudah Didistribusi</option>
                                <option value="belum">Belum Didistribusi</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-auto">
                            <button type="button" id="resetFaktursBtn" class="btn btn-sm btn-outline-secondary" onclick="Array.from(document.querySelectorAll('input[name=\'fakturs[]\']')).forEach(function(cb){cb.checked=false;});">Reset Pilihan</button>
                        </div>
                    </div>
                    <div class="border rounded p-2" style="height:400px; overflow:auto; column-count:7; column-gap:0.75rem;">
                        @foreach(($fakturOptions ?? []) as $fk)
                            <div class="form-check faktur-item" data-text="{{ $fk }}" data-distributed="{{ (isset($fakturDistributedMap[$fk]) && $fakturDistributedMap[$fk]) ? 'ya' : 'belum' }}" style="break-inside: avoid;">
                                <input type="checkbox" class="form-check-input" id="faktur-{{ $loop->index }}" name="fakturs[]" value="{{ $fk }}" @if(in_array($fk, $currentFakturs, true)) checked @endif>
                                <label class="form-check-label" for="faktur-{{ $loop->index }}">{{ $fk }}</label>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">Pilih satu atau beberapa nomor faktur.</small>

                </div>
                <div class="col-12 col-md-9">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1">Stok tersedia (gram)</label>
                            <input type="number" name="stockGram" step="0.001" min="0" value="{{ old('stockGram', (string)($stockGram ?? 0)) }}" class="form-control" placeholder="Masukkan stok gram">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label mb-1">Status yang dihitung</label>
                            @php
                                $opts = ['paid' => 'PAID', 'processing' => 'PROCESSING', 'ready_at_agen' => 'READY @AGEN'];
                                $current = (array) ($statuses ?? ['paid']);
                            @endphp
                            <select name="statuses[]" class="form-select" multiple>
                                @foreach($opts as $val => $label)
                                    <option value="{{ $val }}" @if(in_array($val, $current, true)) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Default: PAID saja.</small>
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-end">
                            <div class="d-grid gap-2 w-100">
                                <input type="hidden" name="viewMode" id="viewModeInput" value="{{ request('viewMode') }}">
                                <button type="submit" id="submitByFakturBtn" class="btn btn-primary w-100">Hitung Stok Berdasarkan Faktur</button>
                                <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#jumlahEmasCalculatorModal">Hitung Jumlah Emas</button>
                                <a href="#antrianTransPoSection" class="btn btn-outline-info w-100">Antrian Trans PO</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm" @if((string)request('viewMode') === 'faktur') style="display:none" @endif>
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex flex-wrap gap-3">
                    <div>Stok dimasukkan: <strong>{{ number_format((float)($stockGram ?? 0), 3) }} g</strong></div>
                    <div>Terpakai: <strong class="text-success">{{ number_format((float)($stockUsed ?? 0), 3) }} g</strong></div>
                    <div>Sisa: <strong class="text-primary">{{ number_format((float)($remainingStock ?? 0), 3) }} g</strong></div>
                    <div>Kebutuhan total (status terpilih): <strong>{{ number_format((float)($totalRequired ?? 0), 3) }} g</strong></div>
                </div>
            </div>

            @if(!empty($allocations))
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th style="width:160px;">Kode PO</th>
                                <th style="width:220px;">Nama &amp; WA</th>
                                <th style="width:280px;">Detail Alamat</th>
                                <th style="width:120px;" class="text-end">Gramasi (g)</th>
                                <th style="width:100px;" class="text-end">Qty</th>
                                <th style="width:120px;">Status</th>
                                <th style="width:140px;" class="text-end">Kebutuhan (g)</th>
                                <th style="width:200px;">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($allocations as $row)
                            <tr>
                                <td>{{ $row['kode_po'] }}</td>
                                <td>
                                    <div class="d-flex align-items-start gap-2">
                                        <div>{{ !empty($row['name']) ? $row['name'] : '-' }}</div>
                                        @php
                                            $waNum = $row['wa'] ?? null;
                                            $waHref = $waNum ? ('https://wa.me/' . preg_replace('/\\D+/', '', $waNum) . '?text=' . rawurlencode('KODE PO: ' . ($row['kode_po'] ?? ''))) : null;
                                        @endphp
                                        @if ($waHref)
                                            <a href="{{ $waHref }}" target="_blank" rel="noopener" class="ms-1" title="Chat via WhatsApp">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#25D366"><path d="M12 0a12 12 0 0 0-10.6 17.9L0 24l6.2-1.6A12 12 0 1 0 12 0zm5.7 17.1c-.2.6-1.2 1.2-1.7 1.3c-.4.1-.9.2-1.5.1c-1.7-.2-3.1-1.1-4.3-2.3c-1.1-1.1-2-2.5-2.3-4.1c-.2-.8 0-1.4.3-1.9c.2-.3.5-.6.8-.6c.2 0 .4 0 .6.1c.2.1.4.5.5.8c.1.3.3.8.2 1c-.1.3-.2.5-.4.7c-.2.3-.5.6-.2 1.1c.3.6.7 1.2 1.2 1.7c.5.5 1 .9 1.6 1.2c.5.3.8.2 1.1-.1c.3-.3.6-.7.9-.9c.3-.2.6-.2 1-.1c.3.1.8.4 1 .6c.3.2.5.5.6.8c.1.3 0 .6-.1.8z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="small text-muted">WA: {{ !empty($row['wa']) ? $row['wa'] : '-' }}</div>
                                </td>
                                <td>
                                    <div><strong>Nama Penerima</strong><br>{{ !empty($row['shipping_name']) ? $row['shipping_name'] : '-' }}</div>
                                    <div class="small">WA Penerima: {{ !empty($row['shipping_phone']) ? $row['shipping_phone'] : '-' }}</div>
                                    <div class="mt-1">{{ !empty($row['shipping_address']) ? $row['shipping_address'] : '-' }}</div>
                                    <div class="small text-muted">{{ !empty($row['shipping_city']) ? $row['shipping_city'] : '-' }}, {{ !empty($row['shipping_province']) ? $row['shipping_province'] : '-' }} {{ !empty($row['shipping_postal_code']) ? $row['shipping_postal_code'] : '-' }}</div>
                                </td>
                                <td class="text-end">{{ isset($row['gramasi']) ? number_format((float)($row['gramasi']), 3) : '-' }}</td>
                                <td class="text-end">{{ isset($row['qty']) ? (int)($row['qty']) : '-' }}</td>
                                <td>{{ strtoupper($row['status'] ?? '') }}</td>
                                <td class="text-end">{{ number_format((float)($row['po_total_gram'] ?? 0), 3) }}</td>
                                <td>
                                    @php
                                        $dt = $row['paid_at'] ?? $row['ordered_at'] ?? $row['created_at'] ?? null;
                                    @endphp
                                    {{ $dt ? $dt->format('Y-m-d H:i') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted">Tidak ada alokasi. Masukkan stok dan pilih status yang ingin dihitung.</div>
            @endif
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12">
            @if(!empty($pricesByFaktur))
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="h6 mb-2">Harga Emas (Master Gold Price) berdasarkan Faktur terpilih</div>
                        @if(!empty($fakturResume))
                            <div class="mb-2 d-flex flex-wrap gap-3">
                                <div>Jumlah Faktur: <strong>{{ (int)($fakturResume['count'] ?? 0) }}</strong></div>
                                <div>Total Qty: <strong>{{ (int)($fakturResume['total_qty'] ?? 0) }}</strong> keping</div>
                                <div>Total Berat: <strong>{{ number_format((float)($fakturResume['total_berat'] ?? 0), 3) }} g</strong></div>
                                @php
                                    $glist = isset($fakturResume['gramasi_unique']) ? (array)$fakturResume['gramasi_unique'] : [];
                                    $glist = array_map(function($v){ return number_format((float)$v, 3); }, $glist);
                                    $glistStr = implode(', ', $glist);
                                @endphp
                                <div>Gramasi unik: <strong>{{ $glistStr ?: '-' }}</strong></div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:160px;">No Faktur</th>
                                        <th class="text-end" style="width:140px;">Gramasi (g)</th>
                                        <th class="text-end" style="width:100px;">Qty</th>
                                        <th class="text-end" style="width:140px;">Berat (g)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(($stocksByFaktur ?? []) as $fk => $rows)
                                        @foreach($rows as $r)
                                            @php
                                                $g = isset($r['gramasi']) ? (float)$r['gramasi'] : 0;
                                                $q = isset($r['qty']) ? (int)$r['qty'] : 0;
                                                $berat = $g * $q;
                                            @endphp
                                            <tr>
                                                <td>{{ $fk }}</td>
                                                <td class="text-end">{{ number_format($g ) }}</td>
                                                <td class="text-end">{{ $q }}</td>
                                                <td class="text-end">{{ number_format($berat) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif(!empty($fakturs))
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Tidak ditemukan data harga emas untuk faktur terpilih.</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
        <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered w-auto">
                            <thead class="table-light">
                                <tr>
                                    <th>Rincian</th>
                                    <th class="text-end">Total Dipilih <span class="small text-muted ms-2">Customer Terpilih: <span id="selectedCustomersCount">0</span></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Gramasi TURUNAN Harga Emas (Master Gold Price) berdasarkan Faktur terpilih yang dipilih</td>
                                    <td id="totalSelectedTurunan" class="text-end">- g</td>
                                </tr>
                                <tr>
                                    <td>Total Antrian Trans PO (Status: PAID) yang dipilih</td>
                                    <td id="totalSelectedAntrian" class="text-end">- g</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        <button type="button" id="checkSyncBtn" class="btn btn-success btn-sm" disabled>Check sinkronkan data</button>
                    </div>
                    <div id="partialPoInfoContainer" class="mt-2 d-none">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered w-auto">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode PO tidak lengkap</th>
                                        <th class="text-end">Terpilih</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="partialPoInfoBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" id="collectSelectedBtn" class="btn btn-outline-secondary btn-sm" disabled>Kumpulkan data terpilih</button>
                    </div>
                    <div id="collectedInfoContainer" class="mt-2 d-none">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered w-auto">
                                <thead class="table-light">
                                    <tr>
                                        <th>Faktur Terpilih (No Faktur)</th>
                                        <th>Trans PO Terpilih (ID | Kode PO | Nama | WA) <span class="small text-muted ms-2">User Terpilih: <span id="collectedUsersCount">0</span></span></th>
                                    </tr>
                                </thead>
                                <tbody id="collectedInfoBody"></tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <button type="button" id="updateFakturStatusBtn" class="btn btn-outline-danger btn-sm" disabled>Update Status Faktur</button>
                                        </td>
                                        <td>
                                            <button type="button" id="updatePoStatusBtn" class="btn btn-outline-primary btn-sm" disabled>Update Status Transaksi PO</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="mismatchInfoContainer" class="mt-2 d-none">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered w-auto">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ketidak Sesuaian</th>
                                        <th class="text-end">Antrian Terpilih</th>
                                        <th class="text-end">Turunan Tersedia</th>
                                        <th class="text-end">Kekurangan</th>
                                        <th>Kode PO Terkait</th>
                                    </tr>
                                </thead>
                                <tbody id="mismatchInfoBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="h6 mb-2">TURUNAN Harga Emas (Master Gold Price) berdasarkan Faktur terpilih</div>
                    <div class="mb-2">
                        Total Gramasi: <strong>{{ number_format((float)($pricesExpandedTotalGram ?? 0), 3) }} g</strong>
                        <div class="small mt-1">Jumlah per gram:
                            1 gr = <strong>{{ (int)($pricesExpandedCounts['1.000'] ?? 0) }}</strong>,
                            2 gr = <strong>{{ (int)($pricesExpandedCounts['2.000'] ?? 0) }}</strong>,
                            3 gr = <strong>{{ (int)($pricesExpandedCounts['3.000'] ?? 0) }}</strong>,
                            5 gr = <strong>{{ (int)($pricesExpandedCounts['5.000'] ?? 0) }}</strong>,
                            10 gr = <strong>{{ (int)($pricesExpandedCounts['10.000'] ?? 0) }}</strong>,
                            25 gr = <strong>{{ (int)($pricesExpandedCounts['25.000'] ?? 0) }}</strong>,
                            50 gr = <strong>{{ (int)($pricesExpandedCounts['50.000'] ?? 0) }}</strong>,
                            100 gr = <strong>{{ (int)($pricesExpandedCounts['100.000'] ?? 0) }}</strong>
                        </div>
                    </div>
                    @if(!empty($pricesExpanded))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:48px;"><input type="checkbox" id="pricesExpandedCheckAll"></th>
                                        <th style="width:160px;">No Faktur</th>
                                        <th class="text-end" style="width:140px;">Gramasi (g)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pricesExpanded as $idx => $row)
                                        @php $val = ($row['no_faktur'] ?? '').'|'.number_format((float)($row['gramasi'] ?? 0), 0).'|'.$idx; @endphp
                                        <tr>
                                            <td class="text-center"><input type="checkbox" class="pricesExpandedCheck" name="pricesExpandedSelected[]" value="{{ $val }}" data-gram="{{ number_format((float)($row['gramasi'] ?? 0), 3, '.', '') }}"></td>
                                            <td>{{ $row['no_faktur'] }}</td>
                                            <td class="text-end">{{ number_format((float)$row['gramasi'], 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">Tidak ada turunan harga (pastikan memilih faktur).</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div id="antrianTransPoSection" class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="h6 mb-0">Antrian Trans PO (Status: PAID & PROCESSING)</div>
                        <select id="antrianStatusFilter" class="form-select form-select-sm" style="width:auto;">
                            <option value="">Semua</option>
                            <option value="paid">PAID</option>
                            <option value="processing">PROCESSING</option>
                        </select>
                    </div>
                    &nbsp;
                    @if(!empty($poQueueResume))
                        <div class="mb-2 d-flex flex-wrap gap-3">
                            <div>Jumlah Faktur: <strong>{{ (int)($poQueueResume['count'] ?? 0) }}</strong></div>
                            <div>Total Qty: <strong>{{ (int)($poQueueResume['total_qty'] ?? 0) }}</strong> keping</div>
                            <div>Total Berat: <strong>{{ number_format((float)($poQueueResume['total_berat'] ?? 0), 3) }} g</strong></div>
                            @php
                                $glist2 = isset($poQueueResume['gramasi_unique']) ? (array)$poQueueResume['gramasi_unique'] : [];
                                $glist2 = array_map(function($v){ return number_format((float)$v, 3); }, $glist2);
                                $glistStr2 = implode(', ', $glist2);
                            @endphp
                            <div>Gramasi unik: <strong>{{ $glistStr2 ?: '-' }}</strong></div>
                        </div>
                    @endif
                    @if(empty($poQueueList))
                        <div class="text-muted">Tidak ada data antrian (status PAID & PROCESSING).</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:48px;"><input type="checkbox" id="antrianCheckAll"></th>
                                        <th class="text-center" style="width:60px;">No.</th>
                                        <th style="width:260px;">Kode PO</th>
                                        <th style="width:150px;">Nama Customer</th>
                                        <th style="width:120px;">Status</th>
                                        <th class="text-end" style="width:140px;">Gramasi (g)</th>
                                        <th class="text-end" style="width:100px;">Qty</th>
                                        <th class="text-end" style="width:160px;">Total Gram (g)</th>
                                        <th class="text-end" style="width:160px;">Akumulasi (g)</th>
                                        <th class="text-end" style="width:160px;">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $acc = 0.0; @endphp
                                    @foreach(($poQueueList ?? []) as $r)
                                        @php $acc += (float)($r['total_gram'] ?? 0.0); @endphp
                                        <tr>
                                            <td class="text-center"><input type="checkbox" class="antrianCheck" name="antrianSelected[]" value="{{ $r['kode_po'] ?? '' }}" data-totalgram="{{ number_format((float)($r['total_gram'] ?? 0), 3, '.', '') }}" data-customer="{{ $r['customer_name'] ?? '' }}" data-wa="{{ $r['customer_wa'] ?? '' }}" data-kode="{{ $r['kode_po'] ?? '' }}" data-id="{{ (int)($r['po_id'] ?? 0) }}"></td>
                                            <td class="text-end">{{ $loop->iteration }}</td>
                                            <td>{{ $r['kode_po'] ?? '-' }}</td>
                                            <td class="text-truncate" style="max-width:80px;">{{ $r['customer_name'] ?? '-' }}</td>
                                            <td>{{ $r['status'] ?? '-' }}</td>
                                            <td class="text-end">@if(isset($r['gramasi'])) {{ number_format((float)$r['gramasi'], 0) }} @else - @endif</td>
                                            <td class="text-end">@if(isset($r['qty'])) {{ (int)$r['qty'] }} @else - @endif</td>
                                            <td class="text-end">@if(isset($r['total_gram'])) {{ number_format((float)$r['total_gram'], 3) }} @else - @endif</td>
                                            <td class="text-end">{{ number_format((float)$acc, 0) }}</td>
                                            <td class="text-end">{{ $r['paid_at'] ?? $r['processed_at'] ?? $r['created_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <div class="mt-2">
                        <small class="text-muted">Antrian ditampilkan dari Trans PO berstatus PAID atau PROCESSING.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="jumlahEmasCalculatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kalkulator Jumlah Emas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1">Gramasi (g)</label>
                            <input type="number" step="0.001" min="0" id="calcGramasiInput" class="form-control" placeholder="Misal 1.000">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1">Qty (Keping)</label>
                            <input type="number" step="1" min="0" id="calcQtyInput" class="form-control" placeholder="Misal 10">
                        </div>
                        <div class="col-12 d-flex align-items-end">
                            <button type="button" id="calcJumlahEmasBtn" class="btn btn-primary">Hitung</button>
                        </div>
                    </div>
                    <div class="mt-3">Berat total: <strong id="calcJumlahEmasResult">- g</strong></div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted">Hitung berat: gramasi × jumlah keping.</small>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('js')
<script>
window.PRICES_BY_FAKTUR = @json($pricesByFaktur ?? []);
document.addEventListener('DOMContentLoaded', function () {
    var gramasiInput = document.getElementById('calcGramasiInput');
    var qtyInput = document.getElementById('calcQtyInput');
    var resultEl = document.getElementById('calcJumlahEmasResult');
    var btn = document.getElementById('calcJumlahEmasBtn');
    if (btn) {
        btn.addEventListener('click', function () {
            var g = parseFloat((gramasiInput && gramasiInput.value) || '0');
            var q = parseInt((qtyInput && qtyInput.value) || '0', 10);
            if (isNaN(g)) g = 0;
            if (isNaN(q)) q = 0;
            var total = Math.max(0, g) * Math.max(0, q);
            if (resultEl) resultEl.textContent = total.toFixed(3) + ' g';
        });
    }

    var submitBtn = document.getElementById('submitByFakturBtn');
    var viewModeInput = document.getElementById('viewModeInput');
    if (submitBtn) {
        submitBtn.addEventListener('click', function () {
            var chosen = Array.from(document.querySelectorAll('input[name="fakturs[]"]:checked')).map(function(cb){ return cb.value; });
            var itemsMap = window.STOCKS_BY_FAKTUR || {};
            var pricesMap = window.PRICES_BY_FAKTUR || {};
            var total = 0;
            for (var i = 0; i < chosen.length; i++) {
                var fk = chosen[i];
                var list = itemsMap[fk] || [];
                if (Array.isArray(list) && list.length) {
                    for (var j = 0; j < list.length; j++) {
                        var g = parseFloat(list[j].gramasi);
                        var q = parseInt(list[j].qty, 10);
                        if (!isNaN(g) && !isNaN(q)) { total += Math.max(0, g) * Math.max(0, q); }
                    }
                } else {
                    var p = pricesMap[fk];
                    if (p && p.berat !== undefined) {
                        var b = parseFloat(p.berat);
                        if (!isNaN(b)) { total += b; }
                    }
                }
            }
            var stockEl = document.querySelector('input[name="stockGram"]');
            if (stockEl) { stockEl.value = Math.max(0, total).toFixed(3); }
            if (viewModeInput) { viewModeInput.value = 'faktur'; }
        });
    }
    var distribEl = document.getElementById('fakturDistribFilter');
    var searchEl = document.getElementById('fakturSearchInput');
    var doFilter = function () {
        var term = (searchEl && searchEl.value || '').toLowerCase().trim();
        var dist = (distribEl && distribEl.value || '').trim();
        document.querySelectorAll('.faktur-item').forEach(function (item) {
            var text = (item.getAttribute('data-text') || '').toLowerCase();
            var status = (item.getAttribute('data-distributed') || 'belum').trim();
            var okText = (term === '' || text.indexOf(term) !== -1);
            var okDist = (dist === '' || status === dist);
            item.style.display = (okText && okDist) ? '' : 'none';
        });
    };
    if (searchEl) { searchEl.addEventListener('input', doFilter); }
    if (distribEl) { distribEl.addEventListener('change', doFilter); }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var BULK_FAKTUR_STATUS_URL = "{{ route('admin.trans.faktur.distribute-bulk') }}";
    var PO_STATUS_URL_BASE = "{{ url('/admin/trans/po') }}";
    var distribAlertShown = false;
    var mismatchAlertShown = false;
    var setText = function (id, text) {
        var el = document.getElementById(id);
        if (el) el.textContent = text;
    };
    var sumChecked = function (selector, attr) {
        var sum = 0;
        document.querySelectorAll(selector).forEach(function (cb) {
            var v = parseFloat(cb.getAttribute(attr) || '0');
            if (!isNaN(v)) sum += Math.max(0, v);
        });
        return sum;
    };
    var countSelectedCustomers = function () {
        var set = new Set();
        document.querySelectorAll('.antrianCheck:checked').forEach(function (cb) {
            var n = (cb.getAttribute('data-customer') || '').trim();
            if (n) set.add(n);
        });
        return set.size;
    };
    var updateActionButtons = function () {
        var fbtn = document.getElementById('updateFakturStatusBtn');
        var pbtn = document.getElementById('updatePoStatusBtn');
        var fcount = document.querySelectorAll('.pricesExpandedCheck:checked').length;
        var pcount = document.querySelectorAll('.antrianCheck:checked').length;
        if (fbtn) fbtn.disabled = fcount === 0;
        if (pbtn) pbtn.disabled = pcount === 0;
    };
    var updateSelectedTurunan = function () {
        setText('totalSelectedTurunan', sumChecked('.pricesExpandedCheck:checked', 'data-gram').toFixed(3) + ' g');
        updateActionButtons();
    };
    var updateSelectedAntrian = function () {
        setText('totalSelectedAntrian', sumChecked('.antrianCheck:checked', 'data-totalgram').toFixed(3) + ' g');
        setText('selectedCustomersCount', String(countSelectedCustomers()));
        updateActionButtons();
    };
    var maybeAlertDistribution = function () {
        var sTur = sumChecked('.pricesExpandedCheck:checked', 'data-gram');
        var sAnt = sumChecked('.antrianCheck:checked', 'data-totalgram');
        if (sTur > 0 && sAnt >= sTur) {
            if (!distribAlertShown) { distribAlertShown = true; Swal.fire({ icon: 'success', title: 'Emas berhasil didistribusikan', showConfirmButton: false, timer: 1500 }); }
        } else {
            distribAlertShown = false;
        }
    };
    var checkSyncBtn = document.getElementById('checkSyncBtn');
    var collectBtn = document.getElementById('collectSelectedBtn');
    var renderCollectedInfo = function () {
        var fakturs = new Set();
        document.querySelectorAll('.pricesExpandedCheck:checked').forEach(function (cb) {
            var row = cb.closest('tr');
            var no = '';
            if (row) {
                var cell = row.querySelector('td:nth-child(2)');
                if (cell) no = (cell.textContent || '').trim();
            }
            if (no) fakturs.add(no);
        });
        var pos = new Map();
        document.querySelectorAll('.antrianCheck:checked').forEach(function (cb) {
            var id = (cb.getAttribute('data-id') || '').trim();
            var kode = (cb.getAttribute('value') || '').trim();
            var name = (cb.getAttribute('data-customer') || '').trim();
            var wa = (cb.getAttribute('data-wa') || '').trim();
            if (id || kode || name || wa) pos.set(id + '|' + kode, {id:id, kode:kode, name:name, wa:wa});
        });
        var fakturList = Array.from(fakturs).sort();
        var poList = Array.from(pos.values());
        var container = document.getElementById('collectedInfoContainer');
        var body = document.getElementById('collectedInfoBody');
        if (!container || !body) return;
        if (fakturList.length === 0 && poList.length === 0) { container.classList.add('d-none'); body.innerHTML = ''; return; }
        container.classList.remove('d-none');
        var users = new Set();
        poList.forEach(function (pr) { var key = (pr.name || '-') + '|' + (pr.wa || '-'); users.add(key); });
        setText('collectedUsersCount', String(users.size));
        var maxLen = Math.max(fakturList.length, poList.length);
        var rows = [];
        for (var i = 0; i < maxLen; i++) {
            var f = fakturList[i] || '';
            var pr = poList[i] || null;
            var p = pr ? ('#' + (pr.id || '-') + ' | ' + (pr.kode || '-') + ' | ' + (pr.name || '-') + ' | ' + (pr.wa || '-')) : '';
            rows.push('<tr><td>' + f + '</td><td>' + p + '</td></tr>');
        }
        body.innerHTML = rows.join('');
    };
    if (collectBtn) { collectBtn.addEventListener('click', renderCollectedInfo); }
    var getCsrfToken = function () { var el = document.querySelector('meta[name="csrf-token"]'); return el ? el.getAttribute('content') : ''; };
    var getSelectedFakturs = function () { var set = new Set(); document.querySelectorAll('.pricesExpandedCheck:checked').forEach(function (cb) { var row = cb.closest('tr'); var no = ''; if (row) { var cell = row.querySelector('td:nth-child(2)'); if (cell) no = (cell.textContent || '').trim(); } if (no) set.add(no); }); return Array.from(set); };
    var getSelectedPoIds = function () { var ids = []; document.querySelectorAll('.antrianCheck:checked').forEach(function (cb) { var id = (cb.getAttribute('data-id') || '').trim(); if (id) ids.push(id); }); return ids; };
    var attachUpdateActionHandlers = function () {
        var fbtn = document.getElementById('updateFakturStatusBtn');
        var pbtn = document.getElementById('updatePoStatusBtn');
        if (fbtn) {
            fbtn.addEventListener('click', async function () {
                var fakturs = getSelectedFakturs();
                if (!fakturs.length) return;
                var res = await Swal.fire({ title: 'Pilih status pengambilan faktur', input: 'select', inputOptions: { 'belum_diambil':'Belum diambil', 'sudah_diambil':'Sudah diambil' }, inputPlaceholder: 'Pilih status', showCancelButton: true });
                var status = res && res.value ? res.value : null;
                if (!status) return;
                var csrf = getCsrfToken();
                await fetch(BULK_FAKTUR_STATUS_URL, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: JSON.stringify({ no_fakturs: fakturs, status_pengambilan: status }) });
                Swal.fire({ icon: 'success', title: 'Status faktur diperbarui', showConfirmButton: false, timer: 1500 });
            });
        }
        if (pbtn) {
            pbtn.addEventListener('click', async function () {
                var ids = getSelectedPoIds();
                if (!ids.length) return;
                var res = await Swal.fire({ title: 'Pilih status Transaksi PO', input: 'select', inputOptions: { 'pending_payment':'Pending', 'paid':'Paid', 'processing':'Processing', 'ready_at_agen':'Ready @Agen', 'shipped':'Shipped', 'completed':'Completed', 'cancelled':'Cancelled' }, inputPlaceholder: 'Pilih status', showCancelButton: true });
                var status = res && res.value ? res.value : null;
                if (!status) return;
                var csrf = getCsrfToken();
                await Promise.all(ids.map(function (id) { var url = PO_STATUS_URL_BASE + '/' + id + '/status'; return fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: JSON.stringify({ status: status }) }); }));
                Swal.fire({ icon: 'success', title: 'Status PO diperbarui', showConfirmButton: false, timer: 1500 });
            });
        }
    };
    var computeSelectedCounts = function (selector, attr) {
        var m = new Map();
        document.querySelectorAll(selector).forEach(function (cb) {
            var v = parseFloat(cb.getAttribute(attr) || '0');
            if (isNaN(v) || v <= 0) return;
            var key = v.toFixed(3);
            m.set(key, (m.get(key) || 0) + 1);
        });
        return m;
    };
    var computeMismatchInfo = function () {
        var tur = computeSelectedCounts('.pricesExpandedCheck:checked', 'data-gram');
        var ant = computeSelectedCounts('.antrianCheck:checked', 'data-totalgram');
        var keys = new Set([].concat(Array.from(tur.keys()), Array.from(ant.keys())));
        var result = [];
        keys.forEach(function (k) {
            var cAnt = ant.get(k) || 0;
            var cTur = tur.get(k) || 0;
            if (cAnt > cTur) {
                var codes = new Set();
                document.querySelectorAll('.antrianCheck:checked').forEach(function (cb) {
                    var g = parseFloat(cb.getAttribute('data-totalgram') || '0');
                    if (!isNaN(g) && g.toFixed(3) === k) {
                        var kode = (cb.getAttribute('data-kode') || '').trim();
                        if (kode) codes.add(kode);
                    }
                });
                result.push({ gram: k, antrian: cAnt, turunan: cTur, shortage: (cAnt - cTur), codes: Array.from(codes).join(', ') });
            }
        });
        result.sort(function (a, b) { return parseFloat(a.gram) - parseFloat(b.gram); });
        return result;
    };
    var renderMismatchInfo = function (list) { var container = document.getElementById('mismatchInfoContainer'); var body = document.getElementById('mismatchInfoBody'); if (!container || !body) return; if (!list.length) { container.classList.add('d-none'); body.innerHTML = ''; return; } container.classList.remove('d-none'); body.innerHTML = list.map(function (it) { return '<tr class="mismatch-row" data-gram="' + parseFloat(it.gram).toFixed(3) + '" style="cursor:pointer;"><td>' + it.gram + ' g</td><td class="text-end">' + it.antrian + '</td><td class="text-end">' + it.turunan + '</td><td class="text-end">' + it.shortage + '</td><td>' + it.codes + '</td></tr>'; }).join(''); attachMismatchRowClickHandlers(); };
    var updateSyncButton = function () {
        var sTur = sumChecked('.pricesExpandedCheck:checked', 'data-gram');
        var sAnt = sumChecked('.antrianCheck:checked', 'data-totalgram');
        var enabled = (sTur > 0) && (sTur.toFixed(3) === sAnt.toFixed(3));
        if (checkSyncBtn) { checkSyncBtn.disabled = !enabled; }
        var mismatches = computeMismatchInfo();
        var partials = computePartialPoList();
        renderMismatchInfo(mismatches);
        var collectBtn = document.getElementById('collectSelectedBtn');
        if (collectBtn) { collectBtn.disabled = partials.length > 0; }
        if (enabled && mismatches.length === 0 && partials.length === 0) {
            if (!mismatchAlertShown) {
                mismatchAlertShown = true;
                Swal.fire({ icon: 'success', title: 'Semua data sudah sinkron', showConfirmButton: false, timer: 1500 });
            }
            clearAntrianHighlights();
            document.querySelectorAll('.antrianCheck').forEach(function (cb) {
                var tr = cb.closest('tr');
                if (!tr) return;
                if (cb.checked) {
                    tr.style.backgroundColor = '#075b35ff';
                    tr.style.color = '#0f5132';
                    tr.style.fontWeight = '700';
                } else {
                    tr.style.backgroundColor = '';
                    tr.style.color = '';
                    tr.style.fontWeight = '';
                }
            });
        } else {
            mismatchAlertShown = false;
        }
    };
    var computePartialPoList = function () {
        var counts = new Map();
        var selectedCounts = new Map();
        document.querySelectorAll('.antrianCheck').forEach(function (cb) {
            var code = (cb.getAttribute('data-kode') || '').trim();
            if (!code) return;
            counts.set(code, (counts.get(code) || 0) + 1);
            if (cb.checked) {
                selectedCounts.set(code, (selectedCounts.get(code) || 0) + 1);
            }
        });
        var list = [];
        counts.forEach(function (total, code) {
            var sel = selectedCounts.get(code) || 0;
            if (sel > 0 && sel < total) {
                list.push({ kode: code, selected: sel, total: total });
            }
        });
        list.sort(function (a, b) { return a.kode.localeCompare(b.kode); });
        return list;
    };
    var renderPartialPoInfo = function (list) { var container = document.getElementById('partialPoInfoContainer'); var body = document.getElementById('partialPoInfoBody'); if (!container || !body) return; if (!list.length) { container.classList.add('d-none'); body.innerHTML = ''; return; } container.classList.remove('d-none'); body.innerHTML = list.map(function (item) { return '<tr class="partial-po-row" data-kode="' + item.kode + '" style="cursor:pointer;"><td>' + item.kode + '</td><td class="text-end">' + item.selected + '</td><td class="text-end">' + item.total + '</td></tr>'; }).join(''); attachPartialPoRowClickHandlers(); };
    var clearAntrianHighlights = function () { document.querySelectorAll('#antrianTransPoSection tbody tr').forEach(function (tr) { tr.style.backgroundColor = ''; tr.style.color = ''; tr.style.fontWeight = ''; }); };
    var highlightAntrianByKode = function (kode) { clearAntrianHighlights(); var matches = []; document.querySelectorAll('.antrianCheck').forEach(function (cb) { var k = (cb.getAttribute('data-kode') || '').trim(); if (k === kode) { var tr = cb.closest('tr'); if (tr) { tr.style.backgroundColor = '#bc2222ff'; tr.style.color = '#b00020'; tr.style.fontWeight = '700'; matches.push(tr); } } }); if (matches.length) { matches[0].scrollIntoView({behavior:'smooth', block:'center'}); } };
    var attachPartialPoRowClickHandlers = function () { document.querySelectorAll('.partial-po-row').forEach(function (row) { row.addEventListener('click', function () { var kode = row.getAttribute('data-kode') || ''; highlightAntrianByKode(kode); }); }); };
    var highlightAntrianByGram = function (gram) { clearAntrianHighlights(); var matches = []; document.querySelectorAll('.antrianCheck').forEach(function (cb) { var g = parseFloat(cb.getAttribute('data-totalgram') || '0'); if (!isNaN(g) && g.toFixed(3) === gram) { var tr = cb.closest('tr'); if (tr) { tr.style.backgroundColor = '#ffcccc'; tr.style.color = '#8b0000'; tr.style.fontWeight = '700'; matches.push(tr); } } }); if (matches.length) { matches[0].scrollIntoView({behavior:'smooth', block:'center'}); } };
    var attachMismatchRowClickHandlers = function () { document.querySelectorAll('.mismatch-row').forEach(function (row) { row.addEventListener('click', function () { var gram = (row.getAttribute('data-gram') || '').trim(); if (gram) highlightAntrianByGram(gram); }); }); };
    var autoDistributeFromTurunan = function () {
        var antrianChecks = Array.from(document.querySelectorAll('.antrianCheck'));
        antrianChecks.forEach(function (cb) { cb.checked = false; });
        var gramsToAssign = [];
        document.querySelectorAll('.pricesExpandedCheck:checked').forEach(function (cb) {
            var g = parseFloat(cb.getAttribute('data-gram') || '0');
            if (!isNaN(g) && g > 0) gramsToAssign.push(g);
        });
        gramsToAssign.forEach(function (g) {
            var target = antrianChecks.find(function (cb) {
                if (cb.checked) return false;
                var ag = parseFloat(cb.getAttribute('data-totalgram') || '0');
                if (isNaN(ag)) return false;
                return ag.toFixed(3) === g.toFixed(3);
            });
            if (target) { target.checked = true; }
        });
        updateSelectedAntrian();
        updateSelectedTurunan();
        maybeAlertDistribution();
        updateSyncButton();
    };
    var antrianAll = document.getElementById('antrianCheckAll');
    if (antrianAll) {
        antrianAll.addEventListener('change', function () {
            document.querySelectorAll('.antrianCheck').forEach(function (cb) { cb.checked = antrianAll.checked; });
            updateSelectedAntrian();
            maybeAlertDistribution();
            updateSyncButton();
        });
    }
    var pricesAll = document.getElementById('pricesExpandedCheckAll');
    if (pricesAll) {
        pricesAll.addEventListener('change', function () {
            document.querySelectorAll('.pricesExpandedCheck').forEach(function (cb) { cb.checked = pricesAll.checked; });
            autoDistributeFromTurunan();
        });
    }
    document.querySelectorAll('.antrianCheck').forEach(function (cb) {
        cb.addEventListener('change', function(){
            updateSelectedAntrian();
            maybeAlertDistribution();
            updateSyncButton();
        });
    });
    var antrianStatusFilter = document.getElementById('antrianStatusFilter');
    var applyAntrianStatusFilter = function () {
        var v = (antrianStatusFilter && antrianStatusFilter.value || '').trim().toLowerCase();
        document.querySelectorAll('#antrianTransPoSection tbody tr').forEach(function (tr) {
            var cell = tr.querySelector('td:nth-child(5)');
            var s = (cell ? cell.textContent : '').trim().toLowerCase();
            tr.style.display = (v === '' || s === v) ? '' : 'none';
        });
    };
    if (antrianStatusFilter) {
        antrianStatusFilter.addEventListener('change', function () { applyAntrianStatusFilter(); });
        applyAntrianStatusFilter();
    }
    document.querySelectorAll('.pricesExpandedCheck').forEach(function (cb) {
        cb.addEventListener('change', function(){
            updateSelectedTurunan();
            autoDistributeFromTurunan();
            updateSyncButton();
        });
    });
    if (checkSyncBtn) { checkSyncBtn.addEventListener('click', function () { var sTur = sumChecked('.pricesExpandedCheck:checked', 'data-gram'); var sAnt = sumChecked('.antrianCheck:checked', 'data-totalgram'); var mismatches = computeMismatchInfo(); var partials = computePartialPoList(); renderPartialPoInfo(partials); renderMismatchInfo(mismatches); var collectBtn = document.getElementById('collectSelectedBtn'); if (collectBtn) { collectBtn.disabled = partials.length > 0; } if (sTur > 0 && sTur.toFixed(3) === sAnt.toFixed(3) && mismatches.length === 0 && partials.length === 0) { Swal.fire({ icon: 'success', title: 'Data sudah sinkron', showConfirmButton: false, timer: 1500 }); clearAntrianHighlights(); document.querySelectorAll('.antrianCheck').forEach(function (cb) { var tr = cb.closest('tr'); if (!tr) return; if (cb.checked) { tr.style.backgroundColor = '#073c24ff'; tr.style.color = '#03100aff'; tr.style.fontWeight = '700'; } else { tr.style.backgroundColor = ''; tr.style.color = ''; tr.style.fontWeight = ''; } }); } }); }
    updateSelectedAntrian();
    updateSelectedTurunan();
    attachUpdateActionHandlers();
    updateSyncButton();
});
</script>
@endsection