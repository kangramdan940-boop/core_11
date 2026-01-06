<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Transaksi PO</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color:#111827; margin: 24px; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .title { font-size:20px; font-weight:700; }
    .muted { color:#6b7280; font-size:12px; }
    .section { margin-bottom:16px; }
    .box { border:1px solid #e5e7eb; border-radius:6px; padding:12px; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding:8px; border-bottom:1px solid #e5e7eb; text-align:left; vertical-align:top; }
    thead th { background:#f8fafc; font-weight:600; }
    tfoot th { background:#f8fafc; }
    .text-end { text-align:right; }
  </style>
</head>
<body>
  @php
    $logoPath = public_path('front/images/logo/logo-light.png');
    $logoData = is_file($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;
    $signPath = public_path('uploads/signatures/admin_signature.png');
    $signData = is_file($signPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($signPath)) : null;
  @endphp
  @if($logoData)
    <div class="watermark">
      <img src="{{ $logoData }}" alt="Jajanemas Watermark" style="position: fixed; top: 26%; left: 15%; width: 70%; opacity: 0.06;" />
    </div>
  @endif
  <div class="header">
    <div>
      <div class="title">Invoice Transaksi</div>
      <div class="muted">Kode Pesanan: {{ $po->kode_po ?? ('PO-' . $po->id) }}</div>
      <div class="muted">Tanggal: {{ \Carbon\Carbon::parse($po->created_at)->format('d M Y H:i') }}</div>
    </div>
    <div style="text-align:right;">
      @if($logoData)
        <img src="{{ $logoData }}" alt="Jajanemas" style="height:30px;" />
      @else
        <div class="muted">Jajanemas.com</div>
      @endif
    </div>
  </div>

  <div class="section" style="display:flex; gap:12px;">
    <div style="flex:1;">
      <div class="box">
        <div style="font-weight:600; margin-bottom:6px;">Ditagihkan Kepada</div>
        <div>{{ optional($po->customer)->full_name ?? '-' }}</div>
        <div class="muted">WA: {{ optional($po->customer)->phone_wa ?? '-' }}</div>
        <div class="muted">Email: {{ optional($po->customer)->email ?? '-' }}</div>
      </div>
    </div>
    <div style="flex:1;">
      <div class="box">
        <div style="font-weight:600; margin-bottom:6px;">Status & Pembayaran</div>
        <div>Status: {{ strtoupper($po->status) }}</div>
        <div>Metode Bayar: {{ $po->payment_method ?? '-' }}</div>
        <div>Referensi: {{ $po->payment_reference ?? '-' }}</div>
        <div>Dibayar: {{ optional($po->paid_at)->format('Y-m-d H:i') ?? '-' }}</div>
      </div>
    </div>
  </div>

  @php
    $qty = (int) ($po->qty ?? 0);
    $gram = (float) ($po->total_gram ?? 0);
    $hargaPerKeping = (float) ($po->harga_per_keping ?? 0);
    $jasaPerUnit = (float) (optional($po->produk)->harga_jasa ?? 0);
    $subtotal = ($hargaPerKeping * $qty) + ($jasaPerUnit * $qty);
    $shippingCost = (float) ($po->shipping_cost ?? 0);
    $totalBayar = (float) ($po->total_amount ?? 0);
  @endphp

  <div class="section">
    <table>
      <thead>
        <tr>
          <th>Deskripsi</th>
          <th class="text-end">Qty</th>
          <th class="text-end">Berat/Unit (g)</th>
          <th class="text-end">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Pre-Order Emas</td>
          <td class="text-end">{{ $qty }}</td>
          <td class="text-end">{{ number_format($gram, 3, ',', '.') }}</td>
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

  @if(($po->delivery_type ?? '') === 'shipping')
    <div class="section box">
      <div style="font-weight:600; margin-bottom:6px;">Data Pengiriman</div>
      <div>{{ $po->shipping_name ?? '-' }} · {{ $po->shipping_phone ?? '-' }}</div>
      <div>{{ $po->shipping_address ?? '-' }}</div>
      <div>{{ $po->shipping_city ?? '-' }}, {{ $po->shipping_province ?? '-' }} {{ $po->shipping_postal_code ?? '' }}</div>
    </div>
  @endif

  @if(($paymentLogs ?? collect())->count() > 0)
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
          @foreach($paymentLogs as $pl)
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

  <div class="section" style="margin-top:24px;">
    <div class="box">
      <div style="font-weight:600; margin-bottom:6px;">Tanda Tangan</div>
      <div>
        @if(isset($signData) && $signData)
          <img src="{{ $signData }}" alt="Tanda Tangan" style="height:70px;" />
        @else
          <div style="height:70px; border-bottom:1px solid #e5e7eb; width:220px;"></div>
        @endif
      </div>
      <div class="muted" style="margin-top:6px;">Ditandatangani oleh: {{ auth()->user()->name ?? 'Admin' }}</div>
      <div class="muted">Tanggal: {{ now()->format('d M Y H:i') }}</div>
    </div>
  </div>

</body>
</html>