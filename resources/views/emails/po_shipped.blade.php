<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pemberitahuan Pengiriman</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { margin:0; padding:0; background:#f6f8fb; font-family: Calibri, Arial, sans-serif; color:#121927; }
    .wrapper { width:100%; table-layout:fixed; background:#f6f8fb; padding:24px 0; }
    .container { width:100%; max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; }
    .header { background: linear-gradient(90deg, #f4e0a0 0%, #F1E38B 2%, #D4AF37 100%); color:#121927; padding:20px 24px; font-weight:600; font-size:18px; text-align:center; }
    .content { padding:24px; font-size:14px; line-height:1.6; }
    .invoice-box { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 16px; margin: 20px 0; }
    .invoice-table { width: 100%; border-collapse: collapse; }
    .invoice-table td { padding: 8px 0; border-bottom: 1px dashed #e0e0e0; vertical-align: top; }
    .invoice-table tr:last-child td { border-bottom: none; }
    .invoice-label { color: #6b7280; font-weight: 500; width: 35%; }
    .invoice-colon { width: 5%; text-align: center; color: #6b7280; font-weight: 500; }
    .invoice-value { font-weight: 600; text-align: left; color: #121927; }
    .btn { display:inline-block; padding:12px 20px; background:#d9b846; color:#121927 !important; text-decoration:none; border-radius:8px; font-weight:600; }
    .muted { color:#6b7280; font-size:12px; }
    .footer { padding:16px 24px; font-size:12px; color:#6b7280; text-align:center; }
    a { color:#0a58ca; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <div class="header">Pemberitahuan Pengiriman</div>
      <div class="content">
        <p>Assalamu’alaikum <strong>{{ optional($po->customer)->full_name ?? 'Pelanggan' }}</strong>,</p>
        <p>Kami informasikan bahwa pesanan Pre-Order (PO) Anda telah dikirim.</p>

        <div class="invoice-box">
          <h3 style="margin-top:0; margin-bottom:16px; border-bottom:2px solid #d9b846; padding-bottom:8px; font-size:16px;">Detail Pengiriman</h3>
          <table class="invoice-table">
            <tr>
              <td class="invoice-label">Kode Pesanan</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">{{ $po->kode_po ?? ('PO-' . $po->id) }}</td>
            </tr>
            <tr>
              <td class="invoice-label">Tanggal Dikirim</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">{{ optional($po->shipped_at)->format('d M Y H:i') ?? '-' }}</td>
            </tr>
            @php
              $resi = trim((string)($po->resi_number ?? ''));
              $courier = trim((string)($po->resi_courier ?? ''));
              $service = trim((string)($po->resi_service ?? ''));
            @endphp
            @if($resi !== '' || $courier !== '' || $service !== '')
            <tr>
              <td class="invoice-label">Nomor Resi</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">{{ $resi !== '' ? $resi : '-' }}</td>
            </tr>
            <tr>
              <td class="invoice-label">Kurir/Layanan</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">{{ $courier ?: '-' }}{{ $service ? ' — ' . $service : '' }}</td>
            </tr>
            @endif
            @if(($po->delivery_type ?? '') === 'shipping')
            <tr>
              <td class="invoice-label">Alamat Tujuan</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">
                {{ $po->shipping_name ?? '-' }} · {{ $po->shipping_phone ?? '-' }}<br>
                {{ $po->shipping_address ?? '-' }}<br>
                {{ $po->shipping_city ?? '-' }}, {{ $po->shipping_province ?? '-' }} {{ $po->shipping_postal_code ?? '' }}
              </td>
            </tr>
            @endif
          </table>
        </div>

        <p class="muted">Email ini dikirim otomatis untuk memberitahukan bahwa pesanan Anda sedang dikirim.</p>
      </div>

      <p style="text-align:center; margin:0 0 8px;">
        <img src="https://jajanemas.com/front/images/logo/logo-light.png" width="181" height="30" alt="Jajanemas.com" style="display:inline-block;border:0;outline:none;text-decoration:none;width:181px;height:30px;">
      </p>
      <div class="footer">© {{ date('Y') }} Jajanemas.com. All rights reserved.</div>
    </div>
  </div>
</body>
</html>