<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransBuyback;
use App\Models\TransBuybackLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class TransBuybackController extends Controller
{
    private const STATUSES = [
        'pending_review',
        'priced',
        'approved',
        'paid',
        'completed',
        'rejected',
        'cancelled',
    ];

    public function index(Request $request)
    {
        $status = (string) $request->query('status', '');
        $dateFilter = (string) $request->query('date', '');
        $createdDate = (string) $request->query('created_date', '');

        $query = TransBuyback::with(['customer'])->orderByDesc('id');

        if ($status !== '' && in_array($status, self::STATUSES, true)) {
            $query->where('status', $status);
        }
        if ($dateFilter === 'today') {
            $query->whereDate('created_at', now()->toDateString());
        }
        if ($createdDate !== '') {
            $query->whereDate('created_at', $createdDate);
        }

        $buybackTrans = $query->get();

        $countsBase = TransBuyback::query();
        if ($dateFilter === 'today') {
            $countsBase->whereDate('created_at', now()->toDateString());
        } elseif ($createdDate !== '') {
            $countsBase->whereDate('created_at', $createdDate);
        }
        $totalCount = (clone $countsBase)->count();
        $todayCount = (clone TransBuyback::query())->whereDate('created_at', now()->toDateString())->count();
        $rawCounts = (clone $countsBase)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $statusCounts = [];
        foreach (self::STATUSES as $st) {
            $statusCounts[$st] = (int) ($rawCounts[$st] ?? 0);
        }

        return view('admin.trans_buyback.index', compact('buybackTrans', 'statusCounts', 'totalCount', 'todayCount'));
    }

    public function show(int $id)
    {
        $trx = TransBuyback::with(['customer'])->findOrFail($id);
        $logs = TransBuybackLog::where('trans_buyback_id', $trx->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_buyback.show', compact('trx', 'logs'));
    }

    /**
     * Admin memverifikasi emas fisik & menetapkan harga final -> status priced.
     */
    public function setPrice(Request $request, int $id)
    {
        $trx = TransBuyback::findOrFail($id);

        if (!in_array($trx->status, ['pending_review', 'priced'], true)) {
            return back()->with('error', 'Harga hanya dapat ditetapkan saat status pending review / priced.');
        }

        $data = $request->validate([
            'harga_buyback_final' => ['required', 'numeric', 'min:0'],
            'catatan_admin'       => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($trx, $data) {
            $qty = (int) ($trx->qty ?: 1);
            $final = (float) $data['harga_buyback_final'];

            $trx->harga_buyback_final = $final;
            $trx->total_amount = TransBuyback::calculateAmount($final, $qty);
            $trx->status = 'priced';
            if (!$trx->verified_at) {
                $trx->verified_at = now();
            }
            if (array_key_exists('catatan_admin', $data)) {
                $trx->catatan_admin = $data['catatan_admin'];
            }
            $trx->save();

            TransBuybackLog::create([
                'trans_buyback_id' => $trx->id,
                'status'           => 'priced',
                'description'      => 'Admin menetapkan harga final Rp ' . number_format($trx->total_amount, 0, ',', '.') . '.',
            ]);
        });

        return redirect()->route('admin.trans.buyback.show', $trx->id)
            ->with('success', 'Harga final ditetapkan. Menunggu persetujuan customer.');
    }

    /**
     * Admin mencatat transfer dana ke customer (upload bukti) -> status paid.
     */
    public function pay(Request $request, int $id)
    {
        $trx = TransBuyback::findOrFail($id);

        if ($trx->status !== 'approved') {
            return back()->with('error', 'Pembayaran hanya dapat dilakukan setelah customer menyetujui harga.');
        }

        $data = $request->validate([
            'bukti_transfer' => ['required', 'image', 'max:3072'],
        ]);

        $dir = public_path('uploads/buyback_proofs');
        File::ensureDirectoryExists($dir);
        $file = $request->file('bukti_transfer');
        $filename = uniqid('buyback_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        $path = 'uploads/buyback_proofs/' . $filename;

        DB::transaction(function () use ($trx, $path) {
            $trx->bukti_transfer_path = $path;
            $trx->status = 'paid';
            $trx->paid_at = now();
            $trx->save();

            TransBuybackLog::create([
                'trans_buyback_id' => $trx->id,
                'status'           => 'paid',
                'description'      => 'Admin mengunggah bukti transfer dana ke customer.',
            ]);
        });

        return redirect()->route('admin.trans.buyback.show', $trx->id)
            ->with('success', 'Bukti transfer tersimpan. Status: paid.');
    }

    /**
     * Update status manual (mis. completed / rejected).
     */
    public function updateStatus(Request $request, int $id)
    {
        $trx = TransBuyback::findOrFail($id);

        $data = $request->validate([
            'status'        => ['required', Rule::in(['approved', 'completed', 'rejected', 'cancelled'])],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $new = $data['status'];

        if ($trx->status === $new) {
            return redirect()->route('admin.trans.buyback.show', $trx->id)->with('success', 'Status tidak berubah.');
        }

        DB::transaction(function () use ($trx, $new, $data) {
            $trx->status = $new;
            if ($new === 'approved' && !$trx->approved_at) {
                $trx->approved_at = now();
            } elseif ($new === 'completed' && !$trx->completed_at) {
                $trx->completed_at = now();
            } elseif ($new === 'cancelled' && !$trx->cancelled_at) {
                $trx->cancelled_at = now();
            }
            if (array_key_exists('catatan_admin', $data) && $data['catatan_admin'] !== null) {
                $trx->catatan_admin = $data['catatan_admin'];
            }
            $trx->save();

            TransBuybackLog::create([
                'trans_buyback_id' => $trx->id,
                'status'           => $new,
                'description'      => 'Status diperbarui admin menjadi ' . strtoupper($new) . '.'
                    . (!empty($data['catatan_admin']) ? ' Catatan: ' . $data['catatan_admin'] : ''),
            ]);
        });

        return redirect()->route('admin.trans.buyback.show', $trx->id)
            ->with('success', 'Status transaksi buyback diperbarui.');
    }
}
