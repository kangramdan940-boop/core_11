<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MitraWithdrawal;
use App\Models\MasterMitraBrankas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class MitraWithdrawalController extends Controller
{
    public function index(Request $request): View
    {
        $query = MitraWithdrawal::with('mitra')->orderByDesc('id');

        $status = trim((string) $request->query('status', ''));
        if ($status !== '') {
            $query->where('status', $status);
        }

        $createdDate = trim((string) $request->query('created_date', ''));
        if ($createdDate !== '') {
            $query->whereDate('created_at', $createdDate);
        }

        $withdrawals = $query->get();

        return view('admin.mitra_withdrawals.index', compact('withdrawals', 'status', 'createdDate'));
    }

    public function show(MitraWithdrawal $withdrawal): View
    {
        $withdrawal->load('mitra');
        $allowedStatuses = [
            MitraWithdrawal::STATUS_REQUESTED,
            MitraWithdrawal::STATUS_PROCESSING,
            MitraWithdrawal::STATUS_CHECKING,
            MitraWithdrawal::STATUS_COMPLETED,
            MitraWithdrawal::STATUS_CANCELED,
        ];

        return view('admin.mitra_withdrawals.show', compact('withdrawal', 'allowedStatuses'));
    }

    public function updateStatus(Request $request, MitraWithdrawal $withdrawal): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:requested,processing,checking,completed,canceled'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $status = $data['status'];
        $payload = [
            'status' => $status,
            'admin_notes' => $data['admin_notes'] ?? $withdrawal->admin_notes,
        ];

        if ($status === MitraWithdrawal::STATUS_PROCESSING || $status === MitraWithdrawal::STATUS_CHECKING) {
            $payload['processed_at'] = $withdrawal->processed_at ?? now();
        }

        if ($status === MitraWithdrawal::STATUS_COMPLETED) {
            $payload['completed_at'] = now();
        }

        if ($status === MitraWithdrawal::STATUS_CANCELED) {
            $payload['completed_at'] = null;
        }

        $withdrawal->update($payload);

        return redirect()
            ->route('admin.trans.mitra-withdrawals.show', $withdrawal)
            ->with('success', 'Status WD Mitra berhasil diperbarui.');
    }

    public function uploadProof(Request $request, MitraWithdrawal $withdrawal): RedirectResponse
    {
        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $file = $request->file('payment_proof');
        $dir = public_path('uploads/mitra_withdrawals/proofs');
        File::ensureDirectoryExists($dir);

        $filename = 'proof_' . $withdrawal->id . '_' . uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        $withdrawal->update([
            'payment_proof_url' => 'uploads/mitra_withdrawals/proofs/' . $filename,
        ]);

        return redirect()
            ->route('admin.trans.mitra-withdrawals.show', $withdrawal)
            ->with('success', 'Bukti pembayaran berhasil diunggah.');
    }
}