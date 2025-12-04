<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPo;
use App\Models\TransPaymentLog;
use App\Models\TransPoLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransPoController extends Controller
{
    public function index()
    {
        $pos = TransPo::with(['customer', 'agen'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.trans_po.index', compact('pos'));
    }

    public function show(TransPo $po)
    {
        $paymentLogs = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_po.show', compact('po', 'paymentLogs'));
    }

    public function approvePayment(Request $request, TransPo $po)
    {
        $pending = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->where('payment_method', 'manual_transfer')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        if (!$pending) {
            return back()->with('error', 'Tidak ada konfirmasi manual pending untuk PO ini.');
        }

        $pending->status = 'paid';
        $pending->paid_at = now();
        $pending->save();

        $po->status = 'paid';
        $po->payment_method = 'manual_transfer';
        $po->payment_reference = $pending->kode_payment;
        $po->paid_at = now();
        $po->save();

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'Pembayaran manual disetujui admin pada '.now(),
        ]);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Pembayaran disetujui dan status PO diperbarui.');
    }

    public function rejectPayment(Request $request, TransPo $po)
    {
        $pending = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->where('payment_method', 'manual_transfer')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        if (!$pending) {
            return back()->with('error', 'Tidak ada konfirmasi manual pending untuk PO ini.');
        }

        $pending->status = 'failed';
        $pending->failed_at = now();
        $pending->save();

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'Pembayaran manual ditolak admin pada '.now(),
        ]);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Konfirmasi pembayaran ditolak.');
    }

    public function updateStatus(Request $request, TransPo $po)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                'pending_payment','paid','processing','ready_at_agen','shipped','completed','cancelled'
            ])],
        ]);

        $new = $data['status'];

        if ($po->status === $new) {
            return redirect()->route('admin.trans.po.show', $po)->with('success', 'Status tidak berubah.');
        }

        $po->status = $new;

        if ($new === 'paid' && !$po->paid_at) {
            $po->paid_at = now();
        } elseif ($new === 'processing' && !$po->processed_at) {
            $po->processed_at = now();
        } elseif ($new === 'ready_at_agen' && !$po->ready_at_agen_at) {
            $po->ready_at_agen_at = now();
        } elseif ($new === 'shipped' && !$po->shipped_at) {
            $po->shipped_at = now();
        } elseif ($new === 'completed' && !$po->completed_at) {
            $po->completed_at = now();
        } elseif ($new === 'cancelled' && !$po->cancelled_at) {
            $po->cancelled_at = now();
        }

        $po->save();

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Status PO diperbarui.');
    }
}