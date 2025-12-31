<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Notifikasi Customer Sudah Transfer</title>
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
      <div class="header">Notifikasi: Customer Mengaku Sudah Transfer</div>
      <div class="content">
        <p>Assalamu’alaikum <strong>{{ optional($po->agen)->name ?? 'Agen' }}</strong>,</p>
        <p>Customer menyatakan sudah melakukan transfer sesuai total pada pesanan PO, namun mengalami kendala saat mengunggah bukti transfer.</p>

        <div class="invoice-box">
          <h3 style="margin-top:0; margin-bottom:16px; border-bottom:2px solid #d9b846; padding-bottom:8px; font-size:16px;">Detail Pesanan</h3>
          <table class="invoice-table">
            <tr>
              <td class="invoice-label">Kode Pesanan</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">{{ $po->kode_po ?? ('PO-' . $po->id) }}</td>
            </tr>
            <tr>
              <td class="invoice-label">Tanggal Pesanan</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">{{ \Carbon\Carbon::parse($po->created_at)->format('d M Y H:i') }}</td>
            </tr>
            <tr>
              <td class="invoice-label">Total Bayar</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value" style="padding-top:12px; border-top:2px solid #e0e0e0; border-bottom:none; font-size:18px; color:#d9b846;">Rp {{ number_format((float)($po->total_amount ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td class="invoice-label">Nama Customer</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">{{ optional($po->customer)->full_name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="invoice-label">Kontak</td>
              <td class="invoice-colon">:</td>
              <td class="invoice-value">WA: {{ optional($po->customer)->phone_wa ?? '-' }}, Email: {{ optional($po->customer)->email ?? '-' }}</td>
            </tr>
          </table>
        </div>

        <p class="muted">Mohon verifikasi pembayaran secara manual melalui mutasi rekening, lalu perbarui status di sistem jika valid.</p>
        <p><a class="btn" href="{{ route('admin.trans.po.show', $po) }}">Buka Detail Transaksi</a></p>
        @php
          $raw = optional($po->customer)->phone_wa;
          $waPhone = preg_replace('/\D+/', '', (string) $raw);
          if (\Illuminate\Support\Str::startsWith($waPhone, '0')) { $waPhone = '62' . substr($waPhone, 1); }
          $amountDisplay = 'Rp ' . number_format((float)($po->total_amount ?? 0), 0, ',', '.');
          $customerName = optional($po->customer)->full_name ?? '-';
          $msg = 'Halo ' . $customerName . ', terkait order ' . ($po->kode_po ?? ('PO-' . $po->id)) . '. Kami menerima notifikasi Anda sudah transfer (' . $amountDisplay . ') namun ada kendala upload struk. Mohon kirim bukti transfer via WhatsApp ini. Terima kasih.';
          $waUrl = $waPhone ? ('https://wa.me/' . $waPhone . '?text=' . rawurlencode($msg)) : null;
        @endphp
        @if ($waUrl)
          <p><a class="btn" href="{{ $waUrl }}" target="_blank" rel="noopener">Kirim via WhatsApp</a></p>
        @endif
      </div>
      <div class="footer">Email ini otomatis dikirim atas permintaan customer.</div>
    </div>
  </div>
</body>
</html>