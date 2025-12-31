@extends('layouts.admin.master')

@section('title', 'Email Logs - Admin')
@section('sub-title', 'Log Pengiriman Email')
@section('breadcrumbExtra', 'Email Logs')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.email-logs.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-nowrap w-100">
                    <thead class="bg-light bg-opacity-30">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Penerima</th>
                            <th>Subjek</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Pemicu</th>
                            <th>Pesan Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                                <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $log->recipient_email }}</div>
                                    <small class="text-muted">{{ $log->recipient_name ?? '-' }}</small>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($log->subject, 30) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $log->mail_type ?? 'General' }}</span>
                                </td>
                                <td>
                                    @if($log->status === 'success')
                                        <span class="badge bg-success">BERHASIL</span>
                                    @elseif($log->status === 'failed')
                                        <span class="badge bg-danger">GAGAL</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ strtoupper($log->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($log->user)->name ?? 'System' }}</td>
                                <td>
                                    @if($log->error_message)
                                        <span class="text-danger small" title="{{ $log->error_message }}">
                                            {{ \Illuminate\Support\Str::limit($log->error_message, 40) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada data log email.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection