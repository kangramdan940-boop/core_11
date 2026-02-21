<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Bulk ({{ strtoupper($status ?? 'SHIPPED') }})</title>
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

  @if(($pos ?? collect())->count() === 0)
    <div class="muted">Tidak ada data PO dengan status: {{ $status ?? 'shipped' }}.</div>
  @else
    @foreach(($pos ?? collect())->chunk(2) as $pageIdx => $chunk)
      <div class="sheet">
        @foreach($chunk as $po)
          <div class="invoice-block">
          <div class="header">
        <div>
          <div class="title">Invoice Transaksi</div>
          <div class="muted">Kode Pesanan: {{ $po->kode_po ?? ('PO-' . $po->id) }}</div>
          <div class="muted">Tanggal: {{ \Carbon\Carbon::parse($po->created_at)->format('d M Y H:i') }}</div>
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
            <div>{{ optional($po->customer)->full_name ?? '-' }}</div>
            <div class="muted">WA: {{ optional($po->customer)->phone_wa ?? '-' }}</div>
            <div class="muted">Email: {{ optional($po->customer)->email ?? '-' }}</div>
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
        $logs = ($paymentsByPo[$po->id] ?? collect());
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



      @if((($po->delivery_type ?? '') === 'shipping') || (($po->delivery_type ?? '') === 'ship') || (($po->shipping_name ?? '') !== '' || ($po->shipping_address ?? '') !== '' || ($po->shipping_city ?? '') !== '' || ($po->shipping_province ?? '') !== '' || ($po->shipping_postal_code ?? '') !== ''))
      <div class="section" style="margin-top:8px;">
        <div class="box" style="font-size:18px; line-height:1.4;">
          <div style="font-weight:700; margin-bottom:6px; font-size:15px;">Alamat Pengiriman</div>
          <div>{{ $po->shipping_name ?? '-' }} · {{ $po->shipping_phone ?? '-' }}</div>
          <div>{{ $po->shipping_address ?? '-' }}</div>
          <div>{{ $po->shipping_city ?? '-' }}, {{ $po->shipping_province ?? '-' }} {{ $po->shipping_postal_code ?? '' }}</div>
        </div>
      </div>
      @endif
      </div>

        @endforeach
      </div>
    @endforeach
  @endif
</body>
</html>