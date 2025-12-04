<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Customer - Jasa Emas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="h4 mb-3">Pendaftaran Customer</h1>
    <p class="text-muted">Isi data berikut untuk membuat akun customer.</p>

    <form method="POST" action="{{ route('customer.register.submit') }}" class="card p-3 shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input name="name" type="text" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Telepon (WA)</label>
            <input name="phone_wa" type="tel" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="address_line" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Daftar</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('customer.login') }}">Sudah punya akun? Login di sini</a>
    </div>
</div>
</body>
</html>