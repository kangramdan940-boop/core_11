<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Mitra Brankas - Jasa Emas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="h4 mb-3">Login Mitra Brankas</h1>
    <form class="card p-3 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required autocomplete="username">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required autocomplete="current-password">
        </div>
        <button type="button" class="btn btn-primary">Masuk</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('mitra.register') }}">Belum punya akun? Register Mitra</a>
    </div>
</div>
</body>
</html>