@extends('layouts.admin.master')

@section('title', 'WP Setting')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">WP Setting</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">WP Setting</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="card-title mb-0">Preview Halaman Depan</h5>
                            <div class="text-muted small">{{ $previewUrl ?? url('/') }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#goldPriceModal">
                                <i class="ri-refresh-line align-bottom"></i> Sinkronkan & Update Harga Hari Ini
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#floatingPriceModal">
                                <i class="ri-price-tag-3-line align-bottom"></i> Sinkronkan & Update Floating Price & Buyback
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#etalaseEmasModal">
                                <i class="ri-store-2-line align-bottom"></i> CRUD WP Etalase Emas
                            </button>

                        </div>
                    </div>
                    <div class="card-body p-0">
                        <iframe
                            src="{{ $previewUrl ?? url('/') }}"
                            title="Preview Homepage"
                            style="width: 100%; height: calc(100vh - 280px); border: 0;"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
            <div class="modal fade" id="goldPriceModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Harga Emas Hari Ini</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="goldPriceBuy" class="form-label">Harga Beli (IDR)</label>
                                    <input type="number" class="form-control" id="goldPriceBuy" inputmode="numeric" min="0" step="1">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="goldPriceBuyback" class="form-label">Harga Buyback (IDR)</label>
                                    <input type="number" class="form-control" id="goldPriceBuyback" inputmode="numeric" min="0" step="1">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="goldPriceDate" class="form-label">Tanggal Harga</label>
                                    <input type="date" class="form-control" id="goldPriceDate" value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="goldLastUpdated" class="form-label">Last Updated</label>
                                    <input type="text" class="form-control" id="goldLastUpdated" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="goldSource" class="form-label">Sumber</label>
                                    <input type="text" class="form-control" id="goldSource" value="HRTA Gold">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="goldCurrency" class="form-label">Mata Uang</label>
                                    <input type="text" class="form-control" id="goldCurrency" value="IDR">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-outline-primary" id="btnGoldSync">
                                <span class="me-1" id="btnGoldSyncText">Sinkronkan</span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btnGoldSave">
                                <span class="me-1" id="btnGoldSaveText">Simpan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="floatingPriceModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Floating Price & Buyback</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-12 col-md-3">
                                            <label for="fpIcon" class="form-label">Icon</label>
                                            <input type="text" class="form-control" id="fpIcon" placeholder="ri-...">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="fpBrand" class="form-label">Brand</label>
                                            <input type="text" class="form-control" id="fpBrand" placeholder="HRTA Gold">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="fpHarga" class="form-label">Harga</label>
                                            <input type="number" class="form-control" id="fpHarga" inputmode="numeric" min="0" step="1">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="fpBuyback" class="form-label">Buyback</label>
                                            <input type="number" class="form-control" id="fpBuyback" inputmode="numeric" min="0" step="1">
                                        </div>
                                        <div class="col-12 d-flex gap-2 flex-wrap">
                                            <input type="hidden" id="fpId" value="">
                                            <button type="button" class="btn btn-primary" id="btnFpSave">Simpan</button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnFpReset">Reset</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width:80px">ID</th>
                                                    <th style="width:160px">Icon</th>
                                                    <th>Brand</th>
                                                    <th class="text-end" style="width:180px">Harga</th>
                                                    <th class="text-end" style="width:180px">Buyback</th>
                                                    <th style="width:160px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="fpTbody">
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Memuat...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="etalaseEmasModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">WP Etalase Emas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-12 col-md-2">
                                            <label for="eeIcon" class="form-label">Icon</label>
                                            <input type="text" class="form-control" id="eeIcon" placeholder="ri-...">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label for="eeCode" class="form-label">Code</label>
                                            <select class="form-control" id="eeCode" name="code">
                                                <option value="" selected disabled>Pilih tipe</option>
                                                <option value="emas_ready">Emas Ready</option>
                                                <option value="emas_preorder">Emas Pre Order</option>
                                                <option value="buyback">Buyback</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label for="eeBrand" class="form-label">Brand</label>
                                            <input type="text" class="form-control" id="eeBrand" placeholder="Antam">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label for="eeBerat" class="form-label">Berat</label>
                                            <input type="text" class="form-control" id="eeBerat" placeholder="1 gr">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label for="eeStok" class="form-label">Stok</label>
                                            <input type="text" class="form-control" id="eeStok" placeholder="12 pcs">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label for="eeStatus" class="form-label">Status</label>
                                            <input type="text" class="form-control" id="eeStatus" placeholder="Tersedia">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="eeHarga" class="form-label">Harga</label>
                                            <input type="number" class="form-control" id="eeHarga" inputmode="numeric" min="0" step="1">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="eeBuyback" class="form-label">Buyback</label>
                                            <input type="number" class="form-control" id="eeBuyback" inputmode="numeric" min="0" step="1">
                                        </div>
                                        <div class="col-12 d-flex gap-2 flex-wrap">
                                            <input type="hidden" id="eeId" value="">
                                            <button type="button" class="btn btn-primary" id="btnEeSave">Simpan</button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnEeReset">Reset</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width:70px">ID</th>
                                                    <th style="width:140px">Icon</th>
                                                    <th style="width:140px">Code</th>
                                                    <th>Brand</th>
                                                    <th style="width:120px">Berat</th>
                                                    <th style="width:120px">Stok</th>
                                                    <th style="width:120px">Status</th>
                                                    <th class="text-end" style="width:160px">Harga</th>
                                                    <th class="text-end" style="width:160px">Buyback</th>
                                                    <th style="width:140px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="eeTbody">
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted">Memuat...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const buyEl = document.getElementById('goldPriceBuy');
    const buybackEl = document.getElementById('goldPriceBuyback');
    const dateEl = document.getElementById('goldPriceDate');
    const lastUpdatedEl = document.getElementById('goldLastUpdated');
    const sourceEl = document.getElementById('goldSource');
    const currencyEl = document.getElementById('goldCurrency');

    const btnSync = document.getElementById('btnGoldSync');
    const btnSyncText = document.getElementById('btnGoldSyncText');
    const btnSave = document.getElementById('btnGoldSave');
    const btnSaveText = document.getElementById('btnGoldSaveText');

    const setBusy = function (btn, labelEl, busyText, isBusy) {
        if (!btn || !labelEl) return;
        btn.disabled = !!isBusy;
        labelEl.textContent = isBusy ? busyText : (labelEl.getAttribute('data-idle') || labelEl.textContent);
    };

    if (btnSyncText) btnSyncText.setAttribute('data-idle', btnSyncText.textContent);
    if (btnSaveText) btnSaveText.setAttribute('data-idle', btnSaveText.textContent);

    btnSync?.addEventListener('click', async function () {
        setBusy(btnSync, btnSyncText, 'Menyinkronkan...', true);
        try {
            const res = await fetch('{{ route('admin.wp-settings.gold-prices.sync') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });

            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.success) {
                alert(json?.message || 'Gagal sinkronisasi');
                return;
            }

            const d = json.data || {};
            if (buyEl && d.buy_price != null) buyEl.value = Math.round(parseFloat(String(d.buy_price)) || 0);
            if (buybackEl && d.buyback_price != null) buybackEl.value = Math.round(parseFloat(String(d.buyback_price)) || 0);
            if (dateEl && d.price_date) dateEl.value = d.price_date;
            if (lastUpdatedEl) lastUpdatedEl.value = d.last_updated || '';
            if (sourceEl) sourceEl.value = d.source || 'HRTA Gold';
            if (currencyEl) currencyEl.value = d.currency || 'IDR';
        } catch (e) {
            alert('Gagal sinkronisasi');
        } finally {
            setBusy(btnSync, btnSyncText, 'Menyinkronkan...', false);
        }
    });

    btnSave?.addEventListener('click', async function () {
        const payload = {
            buy_price: buyEl?.value ? Number(buyEl.value) : null,
            buyback_price: buybackEl?.value ? Number(buybackEl.value) : null,
            sell_price: buybackEl?.value ? Number(buybackEl.value) : null,
            price_date: dateEl?.value || null,
            last_updated: null,
            source: sourceEl?.value || null,
            currency: currencyEl?.value || null,
            is_active: true,
        };

        setBusy(btnSave, btnSaveText, 'Menyimpan...', true);
        try {
            const res = await fetch('{{ route('admin.wp-settings.gold-prices.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.success) {
                if (json?.errors) {
                    const firstKey = Object.keys(json.errors)[0];
                    alert(json.errors[firstKey]?.[0] || 'Validasi gagal');
                } else {
                    alert(json?.message || 'Gagal menyimpan');
                }
                return;
            }

            alert('Berhasil disimpan');
        } catch (e) {
            alert('Gagal menyimpan');
        } finally {
            setBusy(btnSave, btnSaveText, 'Menyimpan...', false);
        }
    });

    const fpModalEl = document.getElementById('floatingPriceModal');
    const fpTbody = document.getElementById('fpTbody');
    const fpId = document.getElementById('fpId');
    const fpIcon = document.getElementById('fpIcon');
    const fpBrand = document.getElementById('fpBrand');
    const fpHarga = document.getElementById('fpHarga');
    const fpBuyback = document.getElementById('fpBuyback');
    const btnFpSave = document.getElementById('btnFpSave');
    const btnFpReset = document.getElementById('btnFpReset');

    const formatIdr = function (n) {
        const v = Number(n || 0);
        return new Intl.NumberFormat('id-ID').format(v);
    };

    const fpReset = function () {
        if (fpId) fpId.value = '';
        if (fpIcon) fpIcon.value = '';
        if (fpBrand) fpBrand.value = '';
        if (fpHarga) fpHarga.value = '';
        if (fpBuyback) fpBuyback.value = '';
        if (btnFpSave) btnFpSave.textContent = 'Simpan';
    };

    const fpLoad = async function () {
        if (!fpTbody) return;
        fpTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Memuat...</td></tr>';
        const res = await fetch('{{ route('admin.wp-settings.floating-price.index') }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
        });
        const json = await res.json().catch(() => null);
        if (!res.ok || !json || !json.success) {
            fpTbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>';
            return;
        }

        const rows = Array.isArray(json.data) ? json.data : [];
        if (rows.length === 0) {
            fpTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Belum ada data</td></tr>';
            return;
        }

        fpTbody.innerHTML = rows.map(function (r) {
            const icon = r.icon ? String(r.icon) : '';
            const brand = r.brand ? String(r.brand) : '';
            const harga = formatIdr(r.harga);
            const buyback = formatIdr(r.buyback);
            return (
                '<tr>' +
                '<td>' + r.id + '</td>' +
                '<td>' + icon + '</td>' +
                '<td>' + brand + '</td>' +
                '<td class="text-end">' + harga + '</td>' +
                '<td class="text-end">' + buyback + '</td>' +
                '<td>' +
                    '<div class="hstack gap-2">' +
                        '<button type="button" class="btn btn-sm btn-light-primary" data-fp-action="edit" data-fp-id="' + r.id + '" data-fp-icon="' + encodeURIComponent(icon) + '" data-fp-brand="' + encodeURIComponent(brand) + '" data-fp-harga="' + (r.harga ?? 0) + '" data-fp-buyback="' + (r.buyback ?? 0) + '"><i class="ri-pencil-line"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-light-danger" data-fp-action="delete" data-fp-id="' + r.id + '"><i class="ri-delete-bin-line"></i></button>' +
                    '</div>' +
                '</td>' +
                '</tr>'
            );
        }).join('');
    };

    fpModalEl?.addEventListener('shown.bs.modal', function () {
        fpReset();
        fpLoad();
    });

    fpTbody?.addEventListener('click', async function (e) {
        const btn = e.target?.closest('button[data-fp-action]');
        if (!btn) return;
        const action = btn.getAttribute('data-fp-action');
        const id = btn.getAttribute('data-fp-id');
        if (!id) return;

        if (action === 'edit') {
            if (fpId) fpId.value = id;
            if (fpIcon) fpIcon.value = decodeURIComponent(btn.getAttribute('data-fp-icon') || '');
            if (fpBrand) fpBrand.value = decodeURIComponent(btn.getAttribute('data-fp-brand') || '');
            if (fpHarga) fpHarga.value = String(btn.getAttribute('data-fp-harga') || '0');
            if (fpBuyback) fpBuyback.value = String(btn.getAttribute('data-fp-buyback') || '0');
            if (btnFpSave) btnFpSave.textContent = 'Update';
            return;
        }

        if (action === 'delete') {
            if (!confirm('Hapus data ini?')) return;
            const res = await fetch('{{ url('/admin/wp-settings/floating-price') }}/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.success) {
                alert(json?.message || 'Gagal menghapus');
                return;
            }
            fpReset();
            fpLoad();
        }
    });

    btnFpReset?.addEventListener('click', function () {
        fpReset();
    });

    btnFpSave?.addEventListener('click', async function () {
        const payload = {
            icon: fpIcon?.value ? String(fpIcon.value) : null,
            brand: fpBrand?.value ? String(fpBrand.value) : null,
            harga: fpHarga?.value ? Number(fpHarga.value) : null,
            buyback: fpBuyback?.value ? Number(fpBuyback.value) : null,
        };

        const isEdit = !!(fpId && fpId.value);
        const url = isEdit
            ? ('{{ url('/admin/wp-settings/floating-price') }}/' + fpId.value)
            : '{{ route('admin.wp-settings.floating-price.store') }}';
        const method = isEdit ? 'PUT' : 'POST';

        const res = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const json = await res.json().catch(() => null);
        if (!res.ok || !json || !json.success) {
            if (json?.errors) {
                const firstKey = Object.keys(json.errors)[0];
                alert(json.errors[firstKey]?.[0] || 'Validasi gagal');
            } else {
                alert(json?.message || 'Gagal menyimpan');
            }
            return;
        }

        fpReset();
        fpLoad();
    });

    const eeModalEl = document.getElementById('etalaseEmasModal');
    const eeTbody = document.getElementById('eeTbody');
    const eeId = document.getElementById('eeId');
    const eeIcon = document.getElementById('eeIcon');
    const eeCode = document.getElementById('eeCode');
    const eeBrand = document.getElementById('eeBrand');
    const eeBerat = document.getElementById('eeBerat');
    const eeStok = document.getElementById('eeStok');
    const eeStatus = document.getElementById('eeStatus');
    const eeHarga = document.getElementById('eeHarga');
    const eeBuyback = document.getElementById('eeBuyback');
    const btnEeSave = document.getElementById('btnEeSave');
    const btnEeReset = document.getElementById('btnEeReset');

    const eeReset = function () {
        if (eeId) eeId.value = '';
        if (eeIcon) eeIcon.value = '';
        if (eeCode) eeCode.value = '';
        if (eeBrand) eeBrand.value = '';
        if (eeBerat) eeBerat.value = '';
        if (eeStok) eeStok.value = '';
        if (eeStatus) eeStatus.value = '';
        if (eeHarga) eeHarga.value = '';
        if (eeBuyback) eeBuyback.value = '';
        if (btnEeSave) btnEeSave.textContent = 'Simpan';
    };

    const eeLoad = async function () {
        if (!eeTbody) return;
        eeTbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted">Memuat...</td></tr>';
        const res = await fetch('{{ route('admin.wp-settings.etalase-emas.index') }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
        });
        const json = await res.json().catch(() => null);
        if (!res.ok || !json || !json.success) {
            eeTbody.innerHTML = '<tr><td colspan="10" class="text-center text-danger">Gagal memuat data</td></tr>';
            return;
        }

        const rows = Array.isArray(json.data) ? json.data : [];
        if (rows.length === 0) {
            eeTbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted">Belum ada data</td></tr>';
            return;
        }

        eeTbody.innerHTML = rows.map(function (r) {
            const icon = r.icon ? String(r.icon) : '';
            const code = r.code ? String(r.code) : '';
            const brand = r.brand ? String(r.brand) : '';
            const berat = r.berat ? String(r.berat) : '';
            const stok = r.stok ? String(r.stok) : '';
            const status = r.status ? String(r.status) : '';
            const harga = formatIdr(r.harga);
            const buyback = formatIdr(r.buyback);
            return (
                '<tr>' +
                '<td>' + r.id + '</td>' +
                '<td>' + icon + '</td>' +
                '<td>' + code + '</td>' +
                '<td>' + brand + '</td>' +
                '<td>' + berat + '</td>' +
                '<td>' + stok + '</td>' +
                '<td>' + status + '</td>' +
                '<td class="text-end">' + harga + '</td>' +
                '<td class="text-end">' + buyback + '</td>' +
                '<td>' +
                    '<div class="hstack gap-2">' +
                        '<button type="button" class="btn btn-sm btn-light-primary" data-ee-action="edit" data-ee-id="' + r.id + '" data-ee-icon="' + encodeURIComponent(icon) + '" data-ee-code="' + encodeURIComponent(code) + '" data-ee-brand="' + encodeURIComponent(brand) + '" data-ee-berat="' + encodeURIComponent(berat) + '" data-ee-stok="' + encodeURIComponent(stok) + '" data-ee-status="' + encodeURIComponent(status) + '" data-ee-harga="' + (r.harga ?? 0) + '" data-ee-buyback="' + (r.buyback ?? 0) + '"><i class="ri-pencil-line"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-light-danger" data-ee-action="delete" data-ee-id="' + r.id + '"><i class="ri-delete-bin-line"></i></button>' +
                    '</div>' +
                '</td>' +
                '</tr>'
            );
        }).join('');
    };

    eeModalEl?.addEventListener('shown.bs.modal', function () {
        eeReset();
        eeLoad();
    });

    eeTbody?.addEventListener('click', async function (e) {
        const btn = e.target?.closest('button[data-ee-action]');
        if (!btn) return;
        const action = btn.getAttribute('data-ee-action');
        const id = btn.getAttribute('data-ee-id');
        if (!id) return;

        if (action === 'edit') {
            if (eeId) eeId.value = id;
            if (eeIcon) eeIcon.value = decodeURIComponent(btn.getAttribute('data-ee-icon') || '');
            if (eeCode) eeCode.value = decodeURIComponent(btn.getAttribute('data-ee-code') || '');
            if (eeBrand) eeBrand.value = decodeURIComponent(btn.getAttribute('data-ee-brand') || '');
            if (eeBerat) eeBerat.value = decodeURIComponent(btn.getAttribute('data-ee-berat') || '');
            if (eeStok) eeStok.value = decodeURIComponent(btn.getAttribute('data-ee-stok') || '');
            if (eeStatus) eeStatus.value = decodeURIComponent(btn.getAttribute('data-ee-status') || '');
            if (eeHarga) eeHarga.value = String(btn.getAttribute('data-ee-harga') || '0');
            if (eeBuyback) eeBuyback.value = String(btn.getAttribute('data-ee-buyback') || '0');
            if (btnEeSave) btnEeSave.textContent = 'Update';
            return;
        }

        if (action === 'delete') {
            if (!confirm('Hapus data ini?')) return;
            const res = await fetch('{{ url('/admin/wp-settings/etalase-emas') }}/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.success) {
                alert(json?.message || 'Gagal menghapus');
                return;
            }
            eeReset();
            eeLoad();
        }
    });

    btnEeReset?.addEventListener('click', function () {
        eeReset();
    });

    btnEeSave?.addEventListener('click', async function () {
        const payload = {
            icon: eeIcon?.value ? String(eeIcon.value) : null,
            code: eeCode?.value ? String(eeCode.value) : '',
            brand: eeBrand?.value ? String(eeBrand.value) : null,
            berat: eeBerat?.value ? String(eeBerat.value) : null,
            stok: eeStok?.value ? String(eeStok.value) : null,
            status: eeStatus?.value ? String(eeStatus.value) : null,
            harga: eeHarga?.value ? Number(eeHarga.value) : null,
            buyback: eeBuyback?.value ? Number(eeBuyback.value) : null,
        };

        if (!payload.code) {
            alert('Code wajib dipilih');
            return;
        }

        const isEdit = !!(eeId && eeId.value);
        const url = isEdit
            ? ('{{ url('/admin/wp-settings/etalase-emas') }}/' + eeId.value)
            : '{{ route('admin.wp-settings.etalase-emas.store') }}';
        const method = isEdit ? 'PUT' : 'POST';

        const res = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const json = await res.json().catch(() => null);
        if (!res.ok || !json || !json.success) {
            if (json?.errors) {
                const firstKey = Object.keys(json.errors)[0];
                alert(json.errors[firstKey]?.[0] || 'Validasi gagal');
            } else {
                alert(json?.message || 'Gagal menyimpan');
            }
            return;
        }

        eeReset();
        eeLoad();
    });
});
</script>
@endsection