@extends('layouts.admin.master')

@section('title', 'Push Notification Tokens')
@section('pagetitle', 'Dashboard')
@section('sub-title', 'System')
@section('breadcrumbExtra', 'Push Tokens')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <h5 class="card-title mb-0">Expo Push Notification Tokens</h5>
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <input type="text" name="q" class="form-control" placeholder="Cari user/token..." value="{{ request('q') }}">
                <select name="platform" class="form-select" style="width:auto;">
                    <option value="">Semua Platform</option>
                    <option value="android" {{ request('platform') === 'android' ? 'selected' : '' }}>Android</option>
                    <option value="ios" {{ request('platform') === 'ios' ? 'selected' : '' }}>iOS</option>
                </select>
                <select name="is_active" class="form-select" style="width:auto;">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
                @if(request()->hasAny(['q', 'platform', 'is_active']))
                    <a href="{{ route('admin.push-tokens.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle table-nowrap mb-0">
                <thead class="bg-light bg-opacity-30">
                    <tr>
                        <th width="50">ID</th>
                        <th>User</th>
                        <th>Expo Token</th>
                        <th>Device</th>
                        <th>Platform</th>
                        <th>Status</th>
                        <th>Terakhir Digunakan</th>
                        <th>Terdaftar</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->user)
                                    <strong>{{ $item->user->name }}</strong>
                                    <br><small class="text-muted">{{ $item->user->email }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <code class="small">{{ Str::limit($item->expo_push_token, 30) }}</code>
                            </td>
                            <td>{{ $item->device_name ?? '-' }}</td>
                            <td>
                                @if($item->platform === 'android')
                                    <span class="badge bg-success-transparent text-success">Android</span>
                                @elseif($item->platform === 'ios')
                                    <span class="badge bg-info-transparent text-info">iOS</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge rounded-pill bg-success">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $item->last_used_at ? $item->last_used_at->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="hstack gap-2 fs-15">
                                    {{-- Test Notification --}}
                                    <button type="button"
                                            class="btn icon-btn-sm btn-light-info test-notif-btn"
                                            title="Test Notification"
                                            data-token="{{ $item->expo_push_token }}"
                                            data-user="{{ $item->user ? $item->user->name : 'Unknown' }}">
                                        <i class="ri-send-plane-line"></i>
                                    </button>

                                    {{-- Toggle Active --}}
                                    <form action="{{ route('admin.push-tokens.toggle-active', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn icon-btn-sm {{ $item->is_active ? 'btn-light-warning' : 'btn-light-success' }}" title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="{{ $item->is_active ? 'ri-toggle-line' : 'ri-toggle-fill' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <a href="#" class="btn icon-btn-sm btn-light-danger delete-item"
                                       data-action="{{ route('admin.push-tokens.destroy', $item) }}"
                                       data-label="{{ $item->user ? $item->user->name : 'Token #'.$item->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                                <form action="{{ route('admin.push-tokens.destroy', $item) }}" method="POST" class="d-none delete-form">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada push token terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="card-footer d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Test Notification --}}
    <div class="modal fade" id="testNotifModal" tabindex="-1" aria-labelledby="testNotifModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testNotifModalLabel">
                        <i class="ri-send-plane-line me-2"></i>Test Push Notification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i class="ri-information-line me-2 fs-5"></i>
                        <span>Mengirim ke: <strong id="modal-target-user"></strong></span>
                    </div>

                    <form id="testNotifForm">
                        <input type="hidden" id="notif_expo_push_token" name="expo_push_token">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="notif_title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="notif_title" name="title" value="🚀 Notifikasi Baru" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="notif_subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="notif_subtitle" name="subtitle" placeholder="Optional subtitle">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notif_body" class="form-label">Body <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="notif_body" name="body" rows="3" required>Ini adalah test notifikasi dari admin panel.</textarea>
                        </div>

                        <hr>
                        <p class="text-muted small mb-2"><i class="ri-database-2-line me-1"></i> Custom Data (opsional)</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="notif_data_screen" class="form-label">Screen (navigasi)</label>
                                <input type="text" class="form-control" id="notif_data_screen" name="data_screen" placeholder="e.g. Home, OrderDetail">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="notif_data_type" class="form-label">Type</label>
                                <input type="text" class="form-control" id="notif_data_type" name="data_type" placeholder="e.g. TEST_NOTIF, ORDER_UPDATE">
                            </div>
                        </div>
                    </form>

                    {{-- Response area --}}
                    <div id="notif-response" class="d-none mt-3">
                        <hr>
                        <label class="form-label fw-semibold">Response dari Expo:</label>
                        <pre class="bg-light p-3 rounded small" id="notif-response-body" style="max-height:200px;overflow:auto;"></pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="sendTestNotifBtn">
                        <i class="ri-send-plane-fill me-1"></i> Kirim Notifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete confirmation
            document.querySelectorAll('.delete-item').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const label = this.getAttribute('data-label') || 'Token';
                    const row = this.closest('tr');
                    const form = row ? row.querySelector('form.delete-form') : null;
                    if (!form) return;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Anda yakin hapus push token milik <b>${label}</b>?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Test Notification Modal
            const testNotifModal = new bootstrap.Modal(document.getElementById('testNotifModal'));

            document.querySelectorAll('.test-notif-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const token = this.getAttribute('data-token');
                    const user = this.getAttribute('data-user');

                    document.getElementById('notif_expo_push_token').value = token;
                    document.getElementById('modal-target-user').textContent = user + ' (' + token + ')';
                    document.getElementById('notif-response').classList.add('d-none');
                    document.getElementById('notif-response-body').textContent = '';

                    testNotifModal.show();
                });
            });

            // Send Test Notification
            document.getElementById('sendTestNotifBtn').addEventListener('click', function() {
                const btn = this;
                const form = document.getElementById('testNotifForm');
                const formData = new FormData(form);

                // Validate required fields
                const title = formData.get('title');
                const body = formData.get('body');
                if (!title || !body) {
                    Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Title dan Body wajib diisi.', confirmButtonText: 'OK' });
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';

                const payload = {};
                formData.forEach((value, key) => {
                    if (value) payload[key] = value;
                });

                fetch("{{ route('admin.push-tokens.send-test') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    const responseDiv = document.getElementById('notif-response');
                    const responseBody = document.getElementById('notif-response-body');

                    responseDiv.classList.remove('d-none');
                    responseBody.textContent = JSON.stringify(data, null, 2);

                    if (data.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonText: 'OK' });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonText: 'OK' });
                    }
                })
                .catch(error => {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan: ' + error.message, confirmButtonText: 'OK' });
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-send-plane-fill me-1"></i> Kirim Notifikasi';
                });
            });

            @if(session('status'))
                Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('status')), confirmButtonText: 'OK' });
            @endif
        });
    </script>
@endsection
