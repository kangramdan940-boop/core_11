<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password Akun Anda</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { margin:0; padding:0; background:#f6f8fb; font-family: Calibri, Arial, sans-serif; color:#121927; }
    .wrapper { width:100%; table-layout:fixed; background:#f6f8fb; padding:24px 0; }
    .container { width:100%; max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; }
    .header { background: linear-gradient(90deg, #f4e0a0 0%, #F1E38B 2%, #D4AF37 100%); color:#121927; padding:20px 24px; font-weight:600; font-size:18px; text-align:center; }
    .content { padding:24px; font-size:14px; line-height:1.6; }
    .btn { display:inline-block; padding:12px 20px; background:#d9b846; color:#121927 !important; text-decoration:none; border-radius:8px; font-weight:600; }
    .muted { color:#6b7280; font-size:12px; }
    .footer { padding:16px 24px; font-size:12px; color:#6b7280; text-align:center; }
    a { color:#0a58ca; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <div class="header">Reset Password Akun Customer</div>
      <div class="content">
        <p>Halo {{ $name }},</p>
        <p>Anda menerima email ini karena ada permintaan untuk reset password akun Anda.</p>
        <p>Silakan klik tombol di bawah untuk mengatur password baru:</p>
        <p style="text-align:center; margin:24px 0;">
          <a class="btn" href="{{ $resetUrl }}" target="_blank" rel="noopener noreferrer">Atur Password Baru</a>
        </p>
        <p>Jika tombol tidak berfungsi, salin dan buka tautan berikut di browser:</p>
        <p><a href="{{ $resetUrl }}" target="_blank" rel="noopener noreferrer">{{ $resetUrl }}</a></p>
        <p class="muted">Tautan ini berlaku selama 60 menit demi keamanan akun Anda.</p>
        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
      </div>
      <p style="text-align:center; margin:0 0 8px;">
        <img src="https://jajanemas.com/front/images/logo/logo-light.png" width="181" height="30" alt="Jajanemas.com" style="display:inline-block;border:0;outline:none;text-decoration:none;width:181px;height:30px;">
      </p>
      <div class="footer">© {{ date('Y') }} Jajanemas.com. All rights reserved.</div>
    </div>
  </div>
</body>
</html>
