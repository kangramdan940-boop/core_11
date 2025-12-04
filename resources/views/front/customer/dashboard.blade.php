<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Customer - Jasa Emas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <header class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Dashboard Customer</h1>
        <form action="{{ route('customer.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-secondary btn-sm" type="submit">Logout</button>
        </form>
    </header> 

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-2">Halo, {{ auth()->user()->name }}.</p>
            <p class="text-muted">Di sini Anda dapat melihat ringkasan transaksi dan cicilan emas Anda.</p>
            <hr>
            <div class="d-flex gap-2 mb-2">
                <a href="{{ route('customer.po.create') }}" class="btn btn-primary btn-sm">
                    + Buat PO Emas
                </a>
                <a href="{{ route('customer.ready.index') }}" class="btn btn-success btn-sm">
                    ⚡ Beli Emas Ready
                </a>
                <a href="{{ route('customer.cicilan.index') }}" class="btn btn-warning btn-sm">
                    🧮 Cicil Emas
                </a>
            </div>

            <h6 class="mt-3 mb-2">PO Emas Saya</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode PO</th>
                            <th>Total (IDR)</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders ?? [] as $po)
                            <tr>
                                <td>{{ $po->kode_po }}</td>
                                <td>{{ number_format((float)$po->total_amount, 2, ',', '.') }}</td>
                                <td>{{ strtoupper($po->status) }}</td>
                                <td>{{ optional($po->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('customer.po.show', $po) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">Belum ada PO.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4 mb-2">Emas Ready Saya</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total (IDR)</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readyOrders ?? [] as $ready)
                            <tr>
                                <td>{{ $ready->kode_trans }}</td>
                                <td>{{ optional($ready->readyStock)->kode_item ?? '-' }}</td>
                                <td>{{ $ready->qty }}</td>
                                <td>{{ number_format((float)$ready->total_amount, 2, ',', '.') }}</td>
                                <td>{{ strtoupper($ready->status) }}</td>
                                <td>{{ optional($ready->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('customer.ready.show', $ready) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">Belum ada transaksi emas ready.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <h6 class="mt-4 mb-2">Emas Cicilan Saya</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode Kontrak</th>
                    <th>Gramasi</th>
                    <th>Total (IDR)</th>
                    <th>Tenor</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th style="width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contracts ?? [] as $c)
                    <tr>
                        <td>{{ $c->kode_kontrak }}</td>
                        <td>{{ $c->gramasi }}</td>
                        <td>{{ number_format((float)$c->harga_total_kontrak, 2, ',', '.') }}</td>
                        <td>{{ $c->tenor_bulan }} bln</td>
                        <td>{{ strtoupper($c->status) }}</td>
                        <td>{{ optional($c->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                        <td>
                            <a href="{{ route('customer.cicilan.show', $c) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-3">Belum ada kontrak cicilan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>