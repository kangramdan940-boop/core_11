<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransKeranjang;
use App\Models\TransPo;
use App\Models\TransPoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class TransKeranjangController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', '');
        $createdDate = (string) $request->query('created_date', '');

        $allowed = ['pending_payment','paid','processing','ready_at_agen','shipped','completed','cancelled'];
        $query = TransKeranjang::withCount('pos')->orderByDesc('id');

        $aliasGroups = [
            'pending_payment' => ['pending_payment','perlu_dibayar'],
            'paid' => ['paid','terbayar'],
            'processing' => ['processing','diproses'],
            'ready_at_agen' => ['ready_at_agen'],
            'shipped' => ['shipped','dikirim'],
            'completed' => ['completed','selesai'],
            'cancelled' => ['cancelled','dibatalkan'],
        ];

        if ($status !== '' && in_array($status, $allowed, true)) {
            $group = $aliasGroups[$status] ?? [$status];
            $query->whereIn('status_order', $group);
        }
        if ($createdDate !== '') {
            $query->whereDate('created_at', $createdDate);
        }

        $keranjangs = $query->get();

        $countsBase = TransKeranjang::query();
        if ($createdDate !== '') {
            $countsBase->whereDate('created_at', $createdDate);
        }
        $totalCount = (clone $countsBase)->count();
        $rawCounts = (clone $countsBase)
            ->selectRaw('LOWER(COALESCE(status_order, "")) as status_order, COUNT(*) as cnt')
            ->groupBy('status_order')
            ->pluck('cnt', 'status_order')
            ->toArray();
        $statusCounts = [];
        foreach ($aliasGroups as $key => $aliases) {
            $sum = 0;
            foreach ($aliases as $alias) {
                $sum += (int) ($rawCounts[$alias] ?? 0);
            }
            $statusCounts[$key] = $sum;
        }

        return view('admin.trans_keranjang.index', compact('keranjangs', 'statusCounts', 'totalCount'));
    }

    public function show(TransKeranjang $keranjang)
    {
        $pos = TransPo::with(['customer','produk.gramasi'])
            ->where('id_keranjang', (int) $keranjang->id)
            ->orderBy('id')
            ->get();

        return view('admin.trans_keranjang.show', compact('keranjang','pos'));
    }

    public function approvePayment(Request $request, TransKeranjang $keranjang)
    {
        $cur = strtolower((string)($keranjang->status_order ?? ''));
        if (!in_array($cur, ['pending_payment','perlu_dibayar'], true)) {
            return back()->with('error', 'Keranjang tidak dalam status pending_payment/perlu_dibayar.');
        }

        DB::transaction(function () use ($keranjang, $request) {
            $keranjang->status_order = 'paid';
            $keranjang->save();

            $pos = TransPo::where('id_keranjang', (int) $keranjang->id)->get();

            foreach ($pos as $po) {
                if ($po->status === 'pending_payment') {
                    $po->status = 'paid';
                    $po->payment_method = 'manual_transfer';
                    $po->payment_reference = 'KERANJANG:' . (string) ($keranjang->kode_keranjang ?? $keranjang->id);
                    $po->paid_at = now();

                    if (empty($po->estimasi_emas_diterima)) {
                        $po->estimasi_emas_diterima = $this->computeEstimasiEmasDiterima();
                    }

                    $po->save();

                    TransPoLog::create([
                        'trans_po_id' => $po->id,
                        'status'      => $po->status,
                        'description' => 'Pembayaran via keranjang disetujui oleh ' . ($request->user()?->name ?? 'SYSTEM') . ' pada ' . now(),
                    ]);
                }
            }
        });

        return redirect()->route('admin.trans.keranjang.show', $keranjang)->with('success', 'Keranjang disetujui. Semua PO pending menjadi PAID.');
    }


    public function update(Request $request, TransKeranjang $keranjang)
    {
        $current = strtolower((string)($keranjang->status_order ?? ''));
        $syn = [
            'perlu_dibayar' => 'pending_payment',
            'terbayar' => 'paid',
            'diproses' => 'processing',
            'dikirim' => 'shipped',
            'selesai' => 'completed',
            'dibatalkan' => 'cancelled',
        ];
        $currentNorm = $syn[$current] ?? $current;

        $next = strtolower((string)$request->input('status_order', ''));
        $map = [
            'pending_payment' => 'paid',
            'paid' => 'processing',
            'processing' => 'ready_at_agen',
            'ready_at_agen' => 'shipped',
            'shipped' => 'completed',
        ];
        $allowed = ['pending_payment','paid','processing','ready_at_agen','shipped','completed','cancelled'];
        $force = $request->boolean('force');
        $expectedNext = $map[$currentNorm] ?? null;
        if ($force) {
            if (!$next || !in_array($next, $allowed, true)) {
                $message = 'Status tidak dikenal.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return back()->with('error', $message);
            }
        } else {
            if (!$next || $expectedNext !== $next) {
                $message = 'Transisi status tidak valid.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return back()->with('error', $message);
            }
        }

        DB::transaction(function () use ($keranjang, $next, $request) {
            $keranjang->status_order = $next;
            $keranjang->save();

            $pos = TransPo::where('id_keranjang', (int) $keranjang->id)->get();
            foreach ($pos as $po) {
                if ($po->status !== $next) {
                    $po->status = $next;
                    if ($next === 'paid' && !$po->paid_at) {
                        $po->paid_at = now();
                    } elseif ($next === 'processing' && !$po->processed_at) {
                        $po->processed_at = now();
                    } elseif ($next === 'ready_at_agen' && !$po->ready_at_agen_at) {
                        $po->ready_at_agen_at = now();
                    } elseif ($next === 'shipped' && !$po->shipped_at) {
                        $po->shipped_at = now();
                    } elseif ($next === 'completed' && !$po->completed_at) {
                        $po->completed_at = now();
                    }
                    $po->save();

                    TransPoLog::create([
                        'trans_po_id' => $po->id,
                        'status'      => $po->status,
                        'description' => 'Status PO disamakan dengan keranjang oleh ' . ($request->user()?->name ?? 'SYSTEM') . ' pada ' . now(),
                    ]);
                }
            }
        });

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.trans.keranjang.show', $keranjang)->with('success', 'Status keranjang & semua PO terkait diperbarui.');
    }

    private function computeEstimasiEmasDiterima(): string
    {
        $aheadGrams = (float) TransPo::whereIn('status', ['paid','processing'])->sum('total_gram');
        $dailyCap = (float) \App\Models\MasterMitraBrankas::where('is_active', true)->sum('harian_limit_gram');
        $extraDays = $dailyCap > 0 ? (int) ceil($aheadGrams / $dailyCap) : 0;
        return now()->addWeeks(3)->addDays($extraDays)->toDateString();
    }
}