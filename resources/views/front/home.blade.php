<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Jasa Emas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f3f4f6; }
        .hero { padding: 40px 0; }
        .card-role { border: 1px solid #e5e7eb; }
        .brand { font-weight: 700; }
    </style>
</head>
<body>
<div class="container">
    <header class="py-3 d-flex justify-content-between align-items-center">
        <div class="brand">Jasa Emas</div>
        <nav class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.login') }}">Login Admin/Agen</a>
        </nav>
    </header>

    <section class="hero text-center">
        <h1 class="h3">Selamat datang di Jasa Emas</h1>
        <p class="text-muted">Informasi layanan jual/beli, penyimpanan, dan cicilan emas. Silakan pilih peran Anda.</p>
    </section>

    <section class="row g-4">
        <div class="col-md-6">
            <div class="card card-role">
                <div class="card-body">
                    <h2 class="h5">Customer</h2>
                    <p class="text-muted">Akses pembelian, cicilan, dan riwayat transaksi Anda.</p>
                    <div class="d-flex gap-2">
                        <a class="btn btn-primary" href="{{ route('customer.login') }}">Login Customer</a>
                        <a class="btn btn-outline-primary" href="{{ route('customer.register') }}">Register Customer</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-role">
                <div class="card-body">
                    <h2 class="h5">Mitra Brankas</h2>
                    <p class="text-muted">Kelola brankas, stok titipan, dan pelaporan.</p>
                    <div class="d-flex gap-2">
                        <a class="btn btn-success" href="{{ route('mitra.login') }}">Login Mitra</a>
                        <a class="btn btn-outline-success" href="{{ route('mitra.register') }}">Register Mitra</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 text-center text-muted">
        &copy; {{ date('Y') }} Jasa Emas
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
