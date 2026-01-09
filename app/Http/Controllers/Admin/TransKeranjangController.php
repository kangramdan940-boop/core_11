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

        $allowed = ['perlu_dibayar','dikemas','dikirim','dibatalkan','selesai'];
        $query = TransKeranjang::withCount('pos')->orderByDesc('id');

        if ($status !== '' && in_array($status, $allowed, true)) {
            $query->where('status_order', $status);
        }
        if ($createdDate !== '') {
            $query->whereDate('created_at', $createdDate);
        }

        $keranjangs = $query->get();

        return view('admin.trans_keranjang.index', compact('keranjangs'));
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
        if (($keranjang->status_order ?? '') !== 'perlu_dibayar') {
            return back()->with('error', 'Keranjang tidak dalam status perlu_dibayar.');
        }

        DB::transaction(function () use ($keranjang, $request) {
            $keranjang->status_order = 'dikemas';
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

    private function computeEstimasiEmasDiterima(): string
    {
        $aheadGrams = (float) TransPo::whereIn('status', ['paid','processing'])->sum('total_gram');
        $dailyCap = (float) \App\Models\MasterMitraBrankas::where('is_active', true)->sum('harian_limit_gram');
        $extraDays = $dailyCap > 0 ? (int) ceil($aheadGrams / $dailyCap) : 0;
        return now()->addWeeks(3)->addDays($extraDays)->toDateString();
    }
}