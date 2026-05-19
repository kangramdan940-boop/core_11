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
                        <th style="width:100px;">Aksi</th>
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
                                    <form action="{{ route('admin.push-tokens.toggle-active', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn icon-btn-sm {{ $item->is_active ? 'btn-light-warning' : 'btn-light-success' }}" title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="{{ $item->is_active ? 'ri-toggle-line' : 'ri-toggle-fill' }}"></i>
                                        </button>
                                    </form>
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
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            @if(session('status'))
                Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('status')), confirmButtonText: 'OK' });
            @endif
        });
    </script>
@endsection
