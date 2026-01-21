<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransCicilan;

class TransCicilanController extends Controller
{
    public function index()
    {
        $contracts = TransCicilan::with(['customer', 'agen'])
            ->orderByDesc('id')
            ->get();

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

    public function cancelWaitingDpAll(\Illuminate\Http\Request $request)
    {
        $count = 0;
        TransCicilan::where('status', 'menunggu DP')->chunkById(100, function ($items) use (&$count) {
            foreach ($items as $contract) {
                $contract->status = 'cancelled';
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