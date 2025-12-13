<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPo;
use App\Models\TransPoLog;
use App\Models\TransPoMitraKomisi;
use App\Models\MasterMitraBrankas;
use App\Models\MasterMitraKomisi;
use App\Models\TransPoMobilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransPoMitraKomisiController extends Controller
{
    public function store(Request $request, TransPo $po)
    {
        $data = $request->validate([
            'master_mitra_brankas_id' => ['required', 'integer', 'exists:master_mitra_brankas,id'],
            'tanggal_komisi'          => ['required', 'date'],
            'jumlah_gram'             => ['required', 'numeric', 'min:0.001'],
            'catatan'                 => ['nullable', 'string'],
        ]);

        if (!in_array($po->status, ['paid','processing','ready_at_agen','shipped','completed'], true)) {
            return back()->withErrors(['status' => 'Assign komisi hanya diizinkan jika status PO minimal paid.']);
        }

        $mitra = MasterMitraBrankas::findOrFail((int) $data['master_mitra_brankas_id']);
        $limitHarian = (float) ($mitra->harian_limit_gram ?? 0.0);
        if ($limitHarian <= 0.0) {
            return back()->withErrors(['master_mitra_brankas_id' => 'Limit harian mitra belum diatur.']);
        }

        $alreadyAllocatedPo = (float) TransPoMitraKomisi::where('trans_po_id', $po->id)->sum('jumlah_gram');
        $remainingForPo = (float) ($po->total_gram - $alreadyAllocatedPo);
        if ((float) $data['jumlah_gram'] > $remainingForPo) {
            return back()->withErrors(['jumlah_gram' => 'Jumlah gram melebihi sisa gram PO (sisa: ' . number_format($remainingForPo, 3, '.', '') . ').']);
        }

        $allocatedDailyForMitra = (float) TransPoMitraKomisi::where('master_mitra_brankas_id', (int) $mitra->id)
            ->where('tanggal_komisi', $data['tanggal_komisi'])
            ->sum('jumlah_gram');
        $remainingDailyMitra = (float) ($limitHarian - $allocatedDailyForMitra);
        if ((float) $data['jumlah_gram'] > $remainingDailyMitra) {
            return back()->withErrors(['jumlah_gram' => 'Jumlah gram melebihi sisa limit harian mitra (sisa: ' . number_format($remainingDailyMitra, 3, '.', '') . ').']);
        }

        $komisiConfig = MasterMitraKomisi::where('master_mitra_brankas_id', (int) $mitra->id)
            ->where('tipe_transaksi', 'po')
            ->where('is_active', true)
            ->where(function ($q) use ($data) {
                $q->whereNull('berlaku_mulai')->orWhere('berlaku_mulai', '<=', $data['tanggal_komisi']);
            })
            ->where(function ($q) use ($data) {
                $q->whereNull('berlaku_sampai')->orWhere('berlaku_sampai', '>=', $data['tanggal_komisi']);
            })
            ->orderByDesc('id')
            ->first();

        $persen = (float) ($komisiConfig->komisi_persen ?? $mitra->komisi_persen ?? 0.0);
        if ($persen < 0.0) {
            $persen = 0.0;
        }

        $feePerUnit = (float) (optional($po->produk)->harga_jasa ?? 0.0);
        $qtyUnit = (int) ($po->qty ?? 1);
        $feePool = (float) number_format($feePerUnit * $qtyUnit, 2, '.', '');
        $totalMobilitas = (float) TransPoMobilitas::where('trans_po_id', $po->id)->sum('amount');
        $netFeePool = (float) number_format(max(0.0, $feePool - $totalMobilitas), 2, '.', '');
        $poTotalGram = (float) ($po->total_gram ?? 0.0);
        $proportion = $poTotalGram > 0.0 ? (float) number_format(min(1.0, ((float) $data['jumlah_gram'] / $poTotalGram)), 6, '.', '') : 0.0;
        $commissionBase = (float) number_format($netFeePool * $proportion, 2, '.', '');
        $amount = (float) number_format($commissionBase * ($persen / 100), 2, '.', '');

        DB::transaction(function () use ($po, $mitra, $data, $persen, $amount) {
            TransPoMitraKomisi::create([
                'trans_po_id'             => $po->id,
                'master_mitra_brankas_id' => (int) $mitra->id,
                'tanggal_komisi'          => $data['tanggal_komisi'],
                'jumlah_gram'             => (float) $data['jumlah_gram'],
                'komisi_persen'           => $persen,
                'komisi_amount'           => $amount,
                'catatan'                 => $data['catatan'] ?? null,
            ]);

            TransPoLog::create([
                'trans_po_id' => $po->id,
                'status'      => $po->status,
                'description' => 'Assign komisi mitra: ' . ($mitra->nama_lengkap ?? ('MIT-' . $mitra->id)) . ' ' . number_format((float) $data['jumlah_gram'], 3, '.', '') . ' g ' . number_format($persen, 2, '.', '') . '%',
            ]);
        });

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Komisi mitra berhasil ditambahkan.');
    }

    public function destroy(TransPoMitraKomisi $assignment)
    {
        $po = $assignment->po;
        $mitra = $assignment->mitra;

        DB::transaction(function () use ($assignment, $po, $mitra) {
            $assignment->delete();

            if ($po) {
                TransPoLog::create([
                    'trans_po_id' => $po->id,
                    'status'      => $po->status,
                    'description' => 'Hapus assign komisi mitra: ' . ($mitra?->nama_lengkap ?? ('MIT-' . ($mitra?->id ?? ''))) . ' ' . number_format((float) $assignment->jumlah_gram, 3, '.', '') . ' g',
                ]);
            }
        });

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Komisi mitra dihapus.');
    }
}