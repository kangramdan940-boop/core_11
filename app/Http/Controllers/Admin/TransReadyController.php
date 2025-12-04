<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransReady;
use App\Models\TransPaymentLog;
use App\Models\TransReadyLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransReadyController extends Controller
{
    public function index()
    {
        $readyTrans = TransReady::with(['customer', 'agen', 'readyStock'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.trans_ready.index', compact('readyTrans'));
    }

    public function show(TransReady $ready)
    {
        $paymentLogs = TransPaymentLog::where('ref_type', 'ready')
            ->where('ref_id', $ready->id)
            ->orderByDesc('id')
            ->get();
        $logs = TransReadyLog::where('trans_ready_id', $ready->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_ready.show', compact('ready', 'paymentLogs', 'logs'));
    }

    public function updateStatus(Request $request, TransReady $ready)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending_payment','paid','shipped','completed','cancelled'])],
        ]);

        $new = $data['status'];

        if ($ready->status === $new) {
            return redirect()->route('admin.trans.ready.show', $ready)->with('success', 'Status tidak berubah.');
        }

        $ready->status = $new;

        if ($new === 'paid' && !$ready->paid_at) {
            $ready->paid_at = now();
        } elseif ($new === 'shipped' && !$ready->shipped_at) {
            $ready->shipped_at = now();
        } elseif ($new === 'completed' && !$ready->completed_at) {
            $ready->completed_at = now();
        } elseif ($new === 'cancelled' && !$ready->cancelled_at) {
            $ready->cancelled_at = now();
        }

        $ready->save();

        return redirect()->route('admin.trans.ready.show', $ready)->with('success', 'Status transaksi ready diperbarui.');
    }
}