<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransCicilanPayment;
use App\Models\TransPaymentLog;

class TransCicilanPaymentController extends Controller
{
    public function index()
    {
        $payments = TransCicilanPayment::with(['kontrak.customer', 'kontrak.agen'])
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_cicilan_payment.index', compact('payments'));
    }

    public function show(TransCicilanPayment $payment)
    {
        $paymentLogs = TransPaymentLog::where('ref_type', 'cicilan_payment')
            ->where('ref_id', $payment->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_cicilan_payment.show', compact('payment', 'paymentLogs'));
    }

    public function confirmPayment(\Illuminate\Http\Request $request, TransCicilanPayment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('admin.trans.cicilan-payments.show', $payment)->with('error', 'Cicilan sudah diproses.');
        }

        $data = $request->validate([
            'nominal_transfer' => ['required', 'numeric', 'min:0.01'],
            'nama_pengirim'    => ['required', 'string', 'max:150'],
            'bukti_transfer'   => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $f = $request->file('bukti_transfer');
        $dir = public_path('payment_proofs');
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $name = uniqid('proof_') . '.' . $f->getClientOriginalExtension();
        $f->move($dir, $name);
        $path = 'payment_proofs/' . $name;

        TransPaymentLog::create([
            'ref_type'        => 'cicilan_payment',
            'ref_id'          => $payment->id,
            'kode_payment'    => 'PAY-' . date('Ymd-His') . '-' . mt_rand(100, 999),
            'amount'          => (float) $data['nominal_transfer'],
            'currency'        => 'IDR',
            'payment_method'  => 'manual_transfer',
            'provider'        => null,
            'payment_channel' => 'manual',
            'status'          => 'pending',
            'request_payload' => json_encode([
                'sender_name' => $data['nama_pengirim'],
                'proof_path'  => $path,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        return redirect()->route('admin.trans.cicilan-payments.show', $payment)->with('success', 'Konfirmasi pembayaran cicilan diunggah. Menunggu verifikasi.');
    }
}