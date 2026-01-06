<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kwitansi Pembayaran</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color:#111827; margin: 24px; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .title { font-size:20px; font-weight:700; }
    .muted { color:#6b7280; font-size:12px; }
    .section { margin-bottom:16px; }
    .box { border:1px solid #e5e7eb; border-radius:6px; padding:12px; }
    .amount { font-size:26px; font-weight:800; letter-spacing:0.5px; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding:8px; border-bottom:1px solid #e5e7eb; text-align:left; vertical-align:top; }
    thead th { background:#f8fafc; font-weight:600; }
    .text-end { text-align:right; }
  </style>
</head>
<body>
  @php
    $logoPath = public_path('front/images/logo/logo-light.png');
    $logoData = is_file($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;

    $signPath = public_path('uploads/signatures/admin_signature.png');
    $signData = is_file($signPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($signPath)) : null;

    $paidLog = isset($paidLog) ? $paidLog : null;
    $amountPaid = $paidLog ? (float) $paidLog->amount : (float) ($po->total_amount ?? 0);
    $paidAt = $paidLog && $paidLog->paid_at ? \Carbon\Carbon::parse($paidLog->paid_at) : ($po->paid_at ? \Carbon\Carbon::parse($po->paid_at) : null);

    $stampPath = public_path('assets/image.png');
    $stampData = is_file($stampPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($stampPath)) : null;
  @endphp

  @if($logoData)
    <div>
      <img src="{{ $logoData }}" alt="Jajanemas Watermark" style="position: fixed; top: 26%; left: 15%; width: 70%; opacity: 0.06;" />
    </div>
  @endif

  <div class="header">
    <div>
      <div class="title">Kwitansi Pembayaran</div>
      <div class="muted">Kode Pesanan: {{ $po->kode_po ?? ('PO-' . $po->id) }}</div>
      <div class="muted">Tanggal Dokumen: {{ \Carbon\Carbon::parse($po->created_at)->format('d M Y H:i') }}</div>
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
        <div style="font-weight:600; margin-bottom:6px;">Telah Diterima Dari</div>
        <div>{{ optional($po->customer)->full_name ?? '-' }}</div>
        <div class="muted">WA: {{ optional($po->customer)->phone_wa ?? '-' }}</div>
        <div class="muted">Email: {{ optional($po->customer)->email ?? '-' }}</div>
      </div>
    </div>
    <div style="flex:1;">
      <div class="box" style="position: relative;">
        @if($stampData && (($paidLog && $paidLog->status === 'paid') || $po->paid_at || in_array($po->status, ['paid','completed'])))
          <img src="{{ $stampData }}" alt="LUNAS" style="position:absolute; top:8px; left:20%; transform:translateX(-50%) rotate(-12deg); height:200px; opacity:0.32; z-index:10;" />
        @endif
        <div style="font-weight:600; margin-bottom:6px;">Jumlah Dibayar</div>
        <div class="amount">Rp {{ number_format($amountPaid, 0, ',', '.') }}</div>
        <div class="muted" style="margin-top:4px;">Metode: {{ $po->payment_method ?? '-' }} · Referensi: {{ $po->payment_reference ?? '-' }}</div>
        <div class="muted">Dibayar: {{ $paidAt ? $paidAt->format('Y-m-d H:i') : '-' }}</div>
      </div>
    </div>
  </div>

  @php
    $qty = (int) ($po->qty ?? 0);
    $gram = (float) ($po->total_gram ?? 0);
  @endphp

  <div class="section box">
    <div style="font-weight:600; margin-bottom:6px;">Untuk Pembayaran</div>
    <table>
      <thead>
        <tr>
          <th>Deskripsi</th>
          <th class="text-end">Qty</th>
          <th class="text-end">Berat/Unit (g)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Pre-Order Emas</td>
          <td class="text-end">{{ $qty }}</td>
          <td class="text-end">{{ number_format($gram, 3, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>
  </div>

    <div class="section box">
      <div style="font-weight:600; margin-bottom:6px;">Tujuan Pengiriman</div>
      <div>{{ $po->shipping_name ?? '-' }} · {{ $po->shipping_phone ?? '-' }}</div>
      <div>{{ $po->shipping_address ?? '-' }}</div>
      <div>{{ $po->shipping_city ?? '-' }}, {{ $po->shipping_province ?? '-' }} {{ $po->shipping_postal_code ?? '' }}</div>
    </div>

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
      <div class="muted" style="margin-top:6px;">Diterbitkan oleh: {{ auth()->user()->name ?? 'Admin' }}</div>
      <div class="muted">Tanggal: {{ now()->format('d M Y H:i') }}</div>
    </div>
  </div>
</body>
</html>