<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPaymentLog;
use App\Models\TransPo;
use App\Models\TransPoLog;
use App\Models\TransReady;
use App\Models\TransCicilanPayment;
use Illuminate\Http\Request;

class TransPaymentLogController extends Controller
{
    public function index()
    {
        $logs = TransPaymentLog::orderByDesc('id')->paginate(20);
        return view('admin.trans_payment_log.index', compact('logs'));
    }

    public function show(TransPaymentLog $log)
    {
        return view('admin.trans_payment_log.show', compact('log'));
    }

    public function approve(Request $request, TransPaymentLog $log)
    {
        if ($log->status !== 'pending' || $log->payment_method !== 'manual_transfer') {
            return back()->with('error', 'Payment tidak dalam status pending manual.');
        }

        $log->status = 'paid';
        $log->paid_at = now();
        $log->save();

        if ($log->ref_type === 'po' && $log->ref_id) {
            $po = TransPo::find($log->ref_id);
            if ($po) {
                $po->status = 'paid';
                $po->payment_method = 'manual_transfer';
                $po->payment_reference = $log->kode_payment;
                $po->paid_at = now();

                if (!$po->estimasi_emas_diterima) {
                    $aheadGrams = \App\Models\TransPo::whereIn('status', ['paid','processing'])
                        ->where('id', '<>', $po->id)
                        ->sum('total_gram');
                    $dailyCap = (float) \App\Models\MasterMitraBrankas::where('is_active', true)->sum('harian_limit_gram');
                    $extraDays = $dailyCap > 0 ? (int) ceil($aheadGrams / $dailyCap) : 0;
                    $baseDate = now()->addWeeks(3);
                    $computed = $baseDate->copy()->addDays($extraDays);
                    $po->estimasi_emas_diterima = $computed->toDateString();
                }

                $po->save();

                TransPoLog::create([
                    'trans_po_id' => $po->id,
                    'status'      => $po->status,
                    'description' => 'Pembayaran manual disetujui oleh '.($request->user()?->name ?? 'SYSTEM').' pada '.now(),
                ]);

                return redirect()->route('admin.trans.po.show', $po)->with('success', 'Pembayaran disetujui dan status PO diperbarui.');
            }
        } elseif ($log->ref_type === 'ready' && $log->ref_id) {
            $ready = TransReady::find($log->ref_id);
            if ($ready) {
                $ready->status = 'paid';
                $ready->payment_method = 'manual_transfer';
                $ready->payment_reference = $log->kode_payment;
                $ready->paid_at = now();
                $ready->save();
                \App\Models\TransReadyLog::create([
                    'trans_ready_id' => $ready->id,
                    'status'         => $ready->status,
                    'description'    => 'Pembayaran manual disetujui oleh '.($request->user()?->name ?? 'SYSTEM').' pada ' . now(),
                ]);
                return redirect()->route('admin.trans.ready.show', $ready)->with('success', 'Pembayaran disetujui dan status transaksi Ready diperbarui.');
            }
        } elseif ($log->ref_type === 'cicilan_payment' && $log->ref_id) {
            $payment = TransCicilanPayment::find($log->ref_id);
            if ($payment) {
                $payment->status = 'paid';
                $payment->amount_paid = (float) $log->amount;
                $payment->paid_at = now();
                $payment->payment_method = 'manual_transfer';
                $payment->payment_reference = $log->kode_payment;
                $payment->save();
                return redirect()->route('admin.trans.cicilan-payments.show', $payment)->with('success', 'Pembayaran cicilan disetujui.');
            }
        }

        return back()->with('success', 'Pembayaran disetujui.');
    }

    public function reject(Request $request, TransPaymentLog $log)
    {
        if ($log->status !== 'pending' || $log->payment_method !== 'manual_transfer') {
            return back()->with('error', 'Payment tidak dalam status pending manual.');
        }

        $log->status = 'failed';
        $log->failed_at = now();
        $log->save();

        if ($log->ref_type === 'po' && $log->ref_id) {
            $po = TransPo::find($log->ref_id);
            if ($po) {
                TransPoLog::create([
                    'trans_po_id' => $po->id,
                    'status'      => $po->status,
                    'description' => 'Pembayaran manual ditolak oleh '.($request->user()?->name ?? 'SYSTEM').' pada '.now(),
                ]);
                return redirect()->route('admin.trans.po.show', $po)->with('success', 'Pembayaran ditolak.');
            }
        } elseif ($log->ref_type === 'ready' && $log->ref_id) {
            $ready = \App\Models\TransReady::find($log->ref_id);
            if ($ready) {
                \App\Models\TransReadyLog::create([
                    'trans_ready_id' => $ready->id,
                    'status'         => $ready->status,
                    'description'    => 'Pembayaran manual ditolak oleh '.($request->user()?->name ?? 'SYSTEM').' pada ' . now(),
                ]);
                return redirect()->route('admin.trans.ready.show', $ready)->with('success', 'Pembayaran ditolak.');
            }
        } elseif ($log->ref_type === 'cicilan_payment' && $log->ref_id) {
            $payment = TransCicilanPayment::find($log->ref_id);
            if ($payment) {
                return redirect()->route('admin.trans.cicilan-payments.show', $payment)->with('success', 'Pembayaran ditolak.');
            }
        }

        return back()->with('success', 'Pembayaran ditolak.');
    }
}