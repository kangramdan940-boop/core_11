@extends('layouts.admin.master')

@section('title', 'Master Flash Sale - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Flash Sale')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.flash-sales.index'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Master Flash Sale</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.master.flash-sales.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#shareGlobalModal">Bagikan Magic Link</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga Jual</th>
                    <th>Tahun</th>
                    <th>Periode</th>
                    <th>Harga Modal</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $it)
                    <tr>
                        <td>{{ $it->item_name }}</td>
                        <td>{{ number_format((float)$it->harga_jual, 2) }}</td>
                        <td>{{ $it->tahun ?? '-' }}</td>
                        <td>{{ $it->periode ?? '-' }}</td>
                        <td>{{ $it->harga_modal !== null ? number_format((float)$it->harga_modal, 2) : '-' }}</td>
                        <td>{{ optional($it->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.master.flash-sales.edit', $it) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#shareLinkModal"
                                    data-flash-id="{{ $it->id }}"
                                    data-enc="{{ \Illuminate\Support\Facades\Crypt::encryptString($it->periode ?? '') }}">
                                Bagikan
                            </button>
                            <form action="{{ route('admin.master.flash-sales.destroy', $it) }}" method="POST" onsubmit="return confirm('Hapus item ini?')" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="shareLinkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bagikan Magic Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" id="sharePhoneInput" class="form-control" placeholder="cth: 08123456789">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Batas Banyak</label>
                        <input type="number" id="shareQtyInput" class="form-control" min="1" step="1" placeholder="cth: 1">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Masa Berlaku (menit)</label>
                        <input type="number" id="shareExpireMinutesInput" class="form-control" min="1" step="1" placeholder="cth: 60">
                    </div>
                    <hr class="my-2">
                    <div class="mb-2">
                        <label class="form-label">Link</label>
                        <input type="text" id="shareLinkOutput" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="generateShareLinkBtn">Generate</button>
                    <button type="button" class="btn btn-outline-primary" id="copyShareLinkBtn">Copy</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            var selected = { id: null, enc: null };
            var modal = document.getElementById('shareLinkModal');
            modal.addEventListener('show.bs.modal', function (event) {
                var btn = event.relatedTarget;
                selected.id = btn?.getAttribute('data-flash-id') || '';
                selected.enc = btn?.getAttribute('data-enc') || '';
                document.getElementById('sharePhoneInput').value = '';
                document.getElementById('shareQtyInput').value = '';
                document.getElementById('shareLinkOutput').value = '';
            });
            document.getElementById('generateShareLinkBtn').addEventListener('click', async function(){
                var phone = (document.getElementById('sharePhoneInput').value || '').replace(/[^0-9+]/g,'');
                var qty = parseInt(document.getElementById('shareQtyInput').value || '1', 10);
                var mins = parseInt(document.getElementById('shareExpireMinutesInput').value || '60', 10);
                if (!qty || qty < 1) qty = 1;
                if (!mins || mins < 1) mins = 60;
                var expTs = Math.floor(Date.now()/1000) + (mins*60);
                var qresp = await fetch('{{ route('admin.utils.encrypt') }}?val=' + encodeURIComponent(String(qty)));
                var qdata = await qresp.json();
                var qenc = qdata?.token || '';
                var eresp = await fetch('{{ route('admin.utils.encrypt') }}?val=' + encodeURIComponent(String(expTs)));
                var edata = await eresp.json();
                var eenc = edata?.token || '';
                var base = '{{ url('/flash-sale') }}';
                var link = base + '/' + selected.id + '/' + selected.enc + '/' + phone + '/' + eenc + '/' + qenc;
                document.getElementById('shareLinkOutput').value = link;
            });
            document.getElementById('copyShareLinkBtn').addEventListener('click', function(){
                var el = document.getElementById('shareLinkOutput');
                el.select(); el.setSelectionRange(0, 99999);
                try { document.execCommand('copy'); } catch (e) {}
            });
        })();
    </script
>

    <div class="modal fade" id="shareGlobalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bagikan Magic Link (Pilihan Barang)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" id="globalPhoneInput" class="form-control" placeholder="cth: 08123456789">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Batas Banyak</label>
                        <input type="number" id="globalQtyInput" class="form-control" min="1" step="1" placeholder="cth: 1">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Masa Berlaku (menit)</label>
                        <input type="number" id="globalExpireMinutesInput" class="form-control" min="1" step="1" placeholder="cth: 60">
                    </div>
                    <hr class="my-2">
                    <div class="mb-2">
                        <label class="form-label">Nomor Rekening Penerima (masuk link)</label>
                        <input type="text" id="globalBankForLink" class="form-control" value="1277883403 BNI M RAMDAN GUMELAR">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Link</label>
                        <input type="text" id="globalLinkOutput" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="generateGlobalLinkBtn">Generate</button>
                    <button type="button" class="btn btn-outline-primary" id="copyGlobalLinkBtn">Copy</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            document.getElementById('generateGlobalLinkBtn').addEventListener('click', async function(){
                var phone = (document.getElementById('globalPhoneInput').value || '').replace(/[^0-9+]/g,'');
                var qty = parseInt(document.getElementById('globalQtyInput').value || '1', 10);
                var mins = parseInt(document.getElementById('globalExpireMinutesInput').value || '60', 10);
                if (!qty || qty < 1) qty = 1;
                if (!mins || mins < 1) mins = 60;
                var expTs = Math.floor(Date.now()/1000) + (mins*60);
                var qresp = await fetch('{{ route('admin.utils.encrypt') }}?val=' + encodeURIComponent(String(qty)));
                var qdata = await qresp.json();
                var qenc = qdata?.token || '';
                var eresp = await fetch('{{ route('admin.utils.encrypt') }}?val=' + encodeURIComponent(String(expTs)));
                var edata = await eresp.json();
                var eenc = edata?.token || '';
                var bank = document.getElementById('globalBankForLink').value || '';
                var bresp = await fetch('{{ route('admin.utils.encrypt') }}?val=' + encodeURIComponent(bank));
                var bdata = await bresp.json();
                var benc = bdata?.token || '';
                var base = '{{ url('/flash-sale/select') }}';
                var link = base + '/' + phone + '/' + eenc + '/' + qenc + '/' + benc;
                document.getElementById('globalLinkOutput').value = link;
            });
            document.getElementById('copyGlobalLinkBtn').addEventListener('click', function(){
                var el = document.getElementById('globalLinkOutput');
                el.select(); el.setSelectionRange(0, 99999);
                try { document.execCommand('copy'); } catch (e) {}
            });
        })();
    </script>
@endsection