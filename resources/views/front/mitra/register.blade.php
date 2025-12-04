<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Mitra Brankas - Jasa Emas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="h4 mb-3">Pendaftaran Mitra Brankas</h1>
    <p class="text-muted">Isi data berikut untuk menjadi mitra brankas.</p>

    <form class="card p-3 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Nama Perusahaan / Mitra</label>
            <input type="text" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Kontak</label>
            <input type="tel" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" rows="3"></textarea>
        </div>
        <button type="button" class="btn btn-success">Daftar</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('mitra.login') }}">Sudah terdaftar? Login di sini</a>
    </div>
</div>
</body>
</html>