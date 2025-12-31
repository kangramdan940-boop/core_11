<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran PO</title>
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
      <div class="header">Konfirmasi Pembayaran PO</div>
      <div class="content">
        <p>Assalamu’alaikum <strong>{{ optional($po->customer)->full_name ?? 'Pelanggan' }}</strong>,</p>
        
        <p>Terima kasih telah berbelanja di Jajanemas.com. Kami informasikan bahwa pembayaran untuk pesanan Pre-Order (PO) Anda telah berhasil kami terima dan verifikasi.</p>
        
        <p>Saat ini pesanan Anda sedang dalam proses. Selamat menunggu, kami akan segera menghubungi Anda kembali setelah emas siap untuk dikirim.</p>

        <div class="invoice-box">
            <h3 style="margin-top:0; margin-bottom:16px; border-bottom:2px solid #d9b846; padding-bottom:8px; font-size:16px;">Invoice / Detail Pesanan</h3>
            
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
                    <td class="invoice-label">Detail Barang</td>
                    <td class="invoice-colon">:</td>
                    <td class="invoice-value">{{ (int)($po->qty ?? 0) }} pcs @ {{ number_format((float)($po->total_gram ?? 0) / (int)($po->qty ?? 1), 3, ',', '.') }} gr</td>
                </tr>
                <tr>
                    <td class="invoice-label">Total Berat</td>
                    <td class="invoice-colon">:</td>
                    <td class="invoice-value">{{ number_format((float)($po->total_gram ?? 0), 3, ',', '.') }} gr</td>
                </tr>
                <tr>
                    <td class="invoice-label">Metode Pengiriman</td>
                    <td class="invoice-colon">:</td>
                    <td class="invoice-value">{{ strtoupper($po->delivery_type ?? '-') }}</td>
                </tr>
                @if(($po->delivery_type ?? '') == 'shipping')
                <tr>
                    <td class="invoice-label">Kurir</td>
                    <td class="invoice-colon">:</td>
                    <td class="invoice-value">{{ strtoupper($po->courier ?? '-') }} ({{ strtoupper($po->courier_service ?? '-') }})</td>
                </tr>
                @endif
                <tr>
                    <td class="invoice-label" style="padding-top:12px; border-top:2px solid #e0e0e0; border-bottom:none; font-size:16px; color:#121927;">Total Bayar</td>
                    <td class="invoice-colon" style="padding-top:12px; border-top:2px solid #e0e0e0; border-bottom:none;">:</td>
                    <td class="invoice-value" style="padding-top:12px; border-top:2px solid #e0e0e0; border-bottom:none; font-size:18px; color:#d9b846;">Rp {{ number_format((float)($po->total_amount ?? 0), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <p>Simpan email ini sebagai bukti pembayaran yang sah.</p>
        <p class="muted">Jika Anda memiliki pertanyaan terkait pesanan ini, silakan balas email ini atau hubungi layanan pelanggan kami.</p>
      </div>
      
      <p style="text-align:center; margin:0 0 8px;">
        <img src="https://jajanemas.com/front/images/logo/logo-light.png" width="181" height="30" alt="Jajanemas.com" style="display:inline-block;border:0;outline:none;text-decoration:none;width:181px;height:30px;">
      </p>
      <div class="footer">© {{ date('Y') }} Jajanemas.com. All rights reserved.</div>
    </div>
  </div>
</body>
</html>