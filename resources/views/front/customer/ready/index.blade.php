<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Emas Ready - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Item</th>
                            <th>Brand</th>
                            <th>Gramasi (g)</th>
                            <th>Harga (IDR)</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $s)
                            <tr>
                                <td>{{ $s->kode_item }}</td>
                                <td>{{ strtoupper($s->brand) }}</td>
                                <td>{{ number_format((float)$s->gramasi, 3, ',', '.') }}</td>
                                <td>{{ number_format((float)($s->harga_jual_fix ?? $s->harga_jual_minimal ?? 0), 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('customer.ready.stock', $s) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                                    <a href="{{ route('customer.ready.buy', $s) }}" class="btn btn-success btn-sm">Beli</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">Belum ada stok tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-2">
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
</div>
</body>
</html>