<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Delivery Note</title>
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
    .text-end { text-align:right; }
  </style>
</head>
<body>



  <div class="header">
    <div>
      <div class="title">Delivery Note</div>
      <div class="muted">Kode Pesanan: {{ $po->kode_po ?? ('PO-' . $po->id) }}</div>
      <div class="muted">Tanggal: {{ \Carbon\Carbon::parse($po->created_at)->format('d M Y H:i') }}</div>
    </div>

  </div>

    <div class="section box">
      <div style="font-weight:600; margin-bottom:6px;">Tujuan Pengiriman</div>
      <div>{{ $po->shipping_name ?? '-' }} · {{ $po->shipping_phone ?? '-' }}</div>
      <div>{{ $po->shipping_address ?? '-' }}</div>
      <div>{{ $po->shipping_city ?? '-' }}, {{ $po->shipping_province ?? '-' }} {{ $po->shipping_postal_code ?? '' }}</div>
    </div>

  @php
    $qty = (int) ($po->qty ?? 0);
    $gram = (float) ($po->total_gram ?? 0);
  @endphp

  <div class="section">
    <table>
      <thead>
        <tr>
          <th>Item</th>
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
</body>
</html>