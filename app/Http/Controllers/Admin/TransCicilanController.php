<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransCicilan;

class TransCicilanController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $statusParam = (string) ($request->query('status') ?? '');
        $map = [
            'active' => 'active',
            'canceled' => 'canceled',
            'cancelled' => 'canceled',
            'menunggu dp' => 'menunggu DP',
            'menunggu-dp' => 'menunggu DP',
        ];
        $key = strtolower($statusParam);
        $statusFilter = $map[$key] ?? null;

        $query = TransCicilan::with(['customer', 'agen'])->orderByDesc('id');
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        $contracts = $query->get();

        return view('admin.trans_cicilan.index', compact('contracts'));
    }

    public function show(TransCicilan $contract)
    {
        $payments = $contract->cicilanPayments()
            ->orderBy('cicilan_ke')
            ->get();

        return view('admin.trans_cicilan.show', compact('contract', 'payments'));
    }

    public function updateStatus(\Illuminate\Http\Request $request, TransCicilan $contract)
    {
        $allowed = ['menunggu DP','active','pembayaran telat','sudah di bayar','selesai','canceled'];
        $data = $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::in($allowed)],
        ]);

        $new = (string) $data['status'];
        if ($contract->status === $new) {
            return redirect()->route('admin.trans.cicilan.show', $contract)->with('success', 'Status tidak berubah.');
        }

        $old = (string) $contract->status;
        $contract->status = $new;

        if ($old === 'menunggu DP' && $new === 'active') {
            if ($contract->master_gold_ready_stock_id) {
                $stock = \App\Models\MasterGoldReadyStock::find($contract->master_gold_ready_stock_id);
                if ($stock && $stock->status === 'available') {
                    $stock->status = 'reserved';
                    $stock->save();
                }
            }
        }

        if ($new === 'selesai' && !$contract->completed_at) {
            $contract->completed_at = now();
        } elseif ($new === 'canceled' && !$contract->cancelled_at) {
            $contract->cancelled_at = now();
        }

        $contract->save();

        return redirect()->route('admin.trans.cicilan.show', $contract)->with('success', 'Status kontrak diperbarui.');
    }

    public function uploadDpProof(\Illuminate\Http\Request $request, TransCicilan $contract)
    {
        $data = $request->validate([
            'bukti_dp' => ['required','file','mimes:jpg,jpeg,png,webp,pdf','max:5120'],
        ]);
        $f = $request->file('bukti_dp');
        $dir = public_path('bukti_dp');
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $name = uniqid('dp_') . '.' . $f->getClientOriginalExtension();
        $f->move($dir, $name);
        $path = 'bukti_dp/' . $name;

        $contract->file_bukti_bayar_dp = $path;
        $contract->save();

        return redirect()->route('admin.trans.cicilan.index', ['status' => 'active'])->with('success', 'Bukti DP berhasil diupload/diupdate.');
    }

    public function cancelWaitingDpAll(\Illuminate\Http\Request $request)
    {
        $count = 0;
        TransCicilan::where('status', 'menunggu DP')->chunkById(100, function ($items) use (&$count) {
            foreach ($items as $contract) {
                $contract->status = 'canceled';
                if (!$contract->cancelled_at) {
                    $contract->cancelled_at = now();
                }
                $contract->save();
                $count++;
            }
        });

        return redirect()->route('admin.trans.cicilan.index')->with('success', 'Berhasil membatalkan ' . $count . ' kontrak menunggu DP.');
    }
}