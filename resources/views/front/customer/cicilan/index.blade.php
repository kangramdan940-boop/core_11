<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cicil Emas - Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Cicil Emas</h1>
        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-2">Pilih Emas Siap Cicil</h6>
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Item</th>
                        <th>Brand</th>
                        <th>Gramasi</th>
                        <th>Harga Jual (IDR)</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $s)
                        <tr>
                            <td>{{ $s->kode_item }}</td>
                            <td>{{ $s->brand }}</td>
                            <td>{{ $s->gramasi }}</td>
                            <td>{{ number_format((float)$s->harga_jual_fix, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('customer.cicilan.stock', $s) }}" class="btn btn-sm btn-primary">Cicil</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">Belum ada stok emas tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(method_exists($stocks, 'hasPages') && $stocks->hasPages())
                <div class="pt-2">{{ $stocks->links() }}</div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-2">Kontrak Cicilan Saya</h6>
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Kontrak</th>
                        <th>Gramasi</th>
                        <th>Total Kontrak (IDR)</th>
                        <th>Tenor</th>
                        <th>Status</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contracts as $c)
                        <tr>
                            <td>{{ $c->kode_kontrak }}</td>
                            <td>{{ $c->gramasi }}</td>
                            <td>{{ number_format((float)$c->harga_total_kontrak, 2, ',', '.') }}</td>
                            <td>{{ $c->tenor_bulan }} bln</td>
                            <td>{{ strtoupper($c->status) }}</td>
                            <td>
                                <a href="{{ route('customer.cicilan.show', $c) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Belum ada kontrak cicilan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(method_exists($contracts, 'hasPages') && $contracts->hasPages())
                <div class="pt-2">{{ $contracts->links() }}</div>
            @endif
        </div>
    </div>
</div>
</body>
</html>