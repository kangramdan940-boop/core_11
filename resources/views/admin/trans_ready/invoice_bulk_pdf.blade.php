<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Bulk Ready ({{ strtoupper($status ?? 'SHIPPED') }})</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color:#111827; margin: 10px; font-size: 10px; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
    .title { font-size:14px; font-weight:700; }
    .muted { color:#6b7280; font-size:9px; }
    .section { margin-bottom:8px; }
    .box { border:1px solid #e5e7eb; border-radius:6px; padding:6px; }
    table { width:100%; border-collapse: collapse; font-size: 10px; }
    th, td { padding:4px; border-bottom:1px solid #e5e7eb; text-align:left; vertical-align:top; }
    thead th { background:#f8fafc; font-weight:600; }
    tfoot th { background:#f8fafc; }
    .text-end { text-align:right; }
    .sheet { page-break-after: always; }
    .invoice-block { page-break-inside: avoid; break-inside: avoid; margin-bottom: 10px; }
  </style>
</head>
<body>
  @php
    $logoPath = public_path('front/images/logo/logo-light.png');
    $logoData = is_file($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;
    $signPath = public_path('uploads/signatures/admin_signature.png');
    $signData = is_file($signPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($signPath)) : null;
  @endphp

  @if(($items ?? collect())->count() === 0)
    <div class="muted">Tidak ada data Ready dengan status: {{ $status ?? 'shipped' }}.</div>
  @else
    @foreach(($items ?? collect())->chunk(2) as $pageIdx => $chunk)
      <div class="sheet">
        @foreach($chunk as $r)
          <div class="invoice-block">
            <div class="header">
              <div>
                <div class="title">Invoice Transaksi Ready</div>
                <div class="muted">Kode Trans: {{ $r->kode_trans ?? ('RDY-' . $r->id) }}</div>
                <div class="muted">Tanggal: {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') }}</div>
              </div>
              <div style="text-align:right;">
                @if($logoData)
                  <img src="{{ $logoData }}" alt="Jajanemas" style="height:22px;" />
                @else
                  <div class="muted">Jajanemas.com</div>
                @endif
              </div>
            </div>

            <div class="section" style="display:flex; gap:12px;">
              <div style="flex:1;">
                <div class="box">
                  <div style="font-weight:600; margin-bottom:6px;">Ditagihkan Kepada</div>
                  <div>{{ optional($r->customer)->full_name ?? '-' }}</div>
                  <div class="muted">WA: {{ optional($r->customer)->phone_wa ?? '-' }}</div>
                  <div class="muted">Email: {{ optional($r->customer)->email ?? '-' }}</div>
                </div>
              </div>
              <div style="flex:1;">
                <div class="box">
                  <div style="font-weight:600; margin-bottom:6px;">Status & Pembayaran</div>
                  <div>Status: {{ strtoupper($r->status) }}</div>
                  <div>Metode Bayar: {{ $r->payment_method ?? '-' }}</div>
                  <div>Referensi: {{ $r->payment_reference ?? '-' }}</div>
                  <div>Dibayar: {{ optional($r->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
              </div>
            </div>

            @php
              $qty = (int) ($r->qty ?? 0);
              $kodeItem = optional($r->readyStock)->kode_item ?? 'Ready Stock Emas';
              $unitPrice = (float) ($r->harga_jual_satuan ?? 0);
              $subtotal = $unitPrice * $qty;
              $shippingCost = (float) ($r->shipping_cost ?? 0);
              $totalBayar = (float) ($r->total_amount ?? 0);
              $logs = ($paymentsByReady[$r->id] ?? collect());
            @endphp

            <div class="section">
              <table>
                <thead>
                  <tr>
                    <th>Deskripsi</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga/Unit</th>
                    <th class="text-end">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $kodeItem }}</td>
                    <td class="text-end">{{ $qty }}</td>
                    <td class="text-end">{{ number_format($unitPrice, 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($subtotal, 2, ',', '.') }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3" class="text-end">Biaya Pengiriman</th>
                    <th class="text-end">{{ number_format($shippingCost, 2, ',', '.') }}</th>
                  </tr>
                  <tr>
                    <th colspan="3" class="text-end">Total Bayar</th>
                    <th class="text-end">Rp {{ number_format($totalBayar, 0, ',', '.') }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>

            @if(in_array(($r->delivery_type ?? ''), ['ship','shipping'], true))
              <div class="section box">
                <div style="font-weight:600; margin-bottom:6px;">Data Pengiriman</div>
                <div>{{ $r->shipping_name ?? '-' }} · {{ $r->shipping_phone ?? '-' }}</div>
                <div>{{ $r->shipping_address ?? '-' }}</div>
                <div>{{ $r->shipping_city ?? '-' }}, {{ $r->shipping_province ?? '-' }} {{ $r->shipping_postal_code ?? '' }}</div>
              </div>
            @endif

            @if(($logs ?? collect())->count() > 0)
              <div class="section">
                <div style="font-weight:600; margin-bottom:6px;">Riwayat Pembayaran</div>
                <table>
                  <thead>
                    <tr>
                      <th>Kode</th>
                      <th>Status</th>
                      <th class="text-end">Jumlah</th>
                      <th>Metode</th>
                      <th>Dibayar</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($logs as $pl)
                      <tr>
                        <td>{{ $pl->kode_payment }}</td>
                        <td>{{ strtoupper($pl->status) }}</td>
                        <td class="text-end">{{ number_format((float)$pl->amount, 2, ',', '.') }} {{ $pl->currency }}</td>
                        <td>{{ $pl->payment_method ?? '-' }}</td>
                        <td>{{ optional($pl->paid_at)->format('Y-m-d H:i') ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif

            <div class="section" style="margin-top:18px;">
              <div class="box">
                <div style="font-weight:600; margin-bottom:6px;">Tanda Tangan</div>
                <div>
                  @if(isset($signData) && $signData)
                    <img src="{{ $signData }}" alt="Tanda Tangan" style="height:40px;" />
                  @else
                    <div style="height:40px; border-bottom:1px solid #e5e7eb; width:160px;"></div>
                  @endif
                </div>
                <div class="muted" style="margin-top:4px;">Ditandatangani oleh: {{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="muted">Tanggal: {{ now()->format('d M Y H:i') }}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endforeach
  @endif
</body>
</html>