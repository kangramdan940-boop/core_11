<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPo;
use App\Models\TransPoMobilitas;
use App\Models\TransPoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransPoMobilitasController extends Controller
{
    public function store(Request $request, TransPo $po)
    {
        $data = $request->validate([
            'tanggal'  => ['required', 'date'],
            'kategori' => ['required', 'string', 'max:100'],
            'deskripsi'=> ['nullable', 'string'],
            'amount'   => ['required', 'numeric', 'min:0'],
        ]);

        if (!in_array($po->status, ['paid','processing','ready_at_agen','shipped','completed'], true)) {
            return back()->withErrors(['status' => 'Biaya mobilitas hanya diizinkan jika status PO minimal paid.'])->withInput();
        }

        $feePerUnit = (float) (optional($po->produk)->harga_jasa ?? 0.0);
        $qty = (int) ($po->qty ?? 1);
        $feePool = (float) number_format($feePerUnit * $qty, 2, '.', '');

        if ($feePool <= 0.0) {
            return back()->withErrors(['amount' => 'Fee transaksi belum tersedia atau nol.'])->withInput();
        }

        $already = (float) TransPoMobilitas::where('trans_po_id', $po->id)->sum('amount');
        $remaining = (float) number_format(max(0.0, $feePool - $already), 2, '.', '');

        if ((float) $data['amount'] > $remaining) {
            return back()->withErrors(['amount' => 'Jumlah melebihi sisa fee (sisa: ' . number_format($remaining, 2, ',', '.') . ').'])->withInput();
        }

        DB::transaction(function () use ($po, $data) {
            TransPoMobilitas::create([
                'trans_po_id' => $po->id,
                'tanggal'     => $data['tanggal'],
                'kategori'    => $data['kategori'],
                'deskripsi'   => $data['deskripsi'] ?? null,
                'amount'      => (float) number_format((float) $data['amount'], 2, '.', ''),
            ]);

            TransPoLog::create([
                'trans_po_id' => $po->id,
                'status'      => $po->status,
                'description' => 'Tambah biaya mobilitas: ' . $data['kategori'] . ' Rp ' . number_format((float) $data['amount'], 2, ',', '.'),
            ]);
        });

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Biaya mobilitas berhasil ditambahkan.');
    }
}