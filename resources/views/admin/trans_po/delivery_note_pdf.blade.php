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
    .resi { font-size:26px; font-weight:800; letter-spacing:1px; }
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
  @endphp
  @if($logoData)
    <div>
      <img src="{{ $logoData }}" alt="Jajanemas Watermark" style="position: fixed; top: 26%; left: 15%; width: 70%; opacity: 0.06;" />
    </div>
  @endif

  <div class="header">
    <div>
      <div class="title">Delivery Note</div>
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

  <div class="section box">
    <div class="resi">{{ $po->resi_number ?? 'RESI-BELUM-DIISI' }}</div>
    <div class="muted" style="margin-top:4px;">Kurir: {{ $po->resi_courier ?? '-' }} · Layanan: {{ $po->resi_service ?? '-' }}</div>
  </div>

    <div class="section box">
      <div style="font-weight:600; margin-bottom:6px;">Data Pengirim</div>
      <div>jajanemas.com</div>
    </div>

    <div class="section box">
      <div style="font-weight:600; margin-bottom:6px;">Data Penerima</div>
      <div>{{ $po->shipping_name ?? '-' }} · {{ $po->shipping_phone ?? '-' }}</div>
      <div>{{ $po->shipping_address ?? '-' }}</div>
      <div>{{ $po->shipping_city ?? '-' }}, {{ $po->shipping_province ?? '-' }} {{ $po->shipping_postal_code ?? '' }}</div>
    </div>

    <div class="section box">
      <div style="font-weight:600; margin-bottom:6px;">Tanggal Dikirim</div>
      <div>{{ optional($po->shipped_at)->format('Y-m-d H:i') ?? '-' }}</div>
    </div>

  @php
    $qty = (int) ($po->qty ?? 0);
    $gram = (float) ($po->total_gram ?? 0);
    $hargaPerKeping = (float) ($po->harga_per_keping ?? 0);
    $jasaPerUnit = (float) (optional($po->produk)->harga_jasa ?? 0);
    $unitPrice = $hargaPerKeping + $jasaPerUnit;
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
          <th class="text-end">Harga/Unit</th>
          <th class="text-end">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Pre-Order Emas</td>
          <td class="text-end">{{ $qty }}</td>
          <td class="text-end">{{ number_format($gram, 3, ',', '.') }}</td>
          <td class="text-end">{{ number_format($unitPrice, 2, ',', '.') }}</td>
          <td class="text-end">{{ number_format($subtotal, 2, ',', '.') }}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-end">Biaya Pengiriman</th>
          <th class="text-end">{{ number_format($shippingCost, 2, ',', '.') }}</th>
        </tr>
        <tr>
          <th colspan="4" class="text-end">Total Bayar</th>
          <th class="text-end">Rp {{ number_format($totalBayar, 0, ',', '.') }}</th>
        </tr>
      </tfoot>
    </table>
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
      <div class="muted" style="margin-top:6px;">Ditandatangani oleh: {{ auth()->user()->name ?? 'Admin' }}</div>
      <div class="muted">Tanggal: {{ now()->format('d M Y H:i') }}</div>
    </div>
  </div>
</body>
</html>