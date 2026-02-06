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

    public function overdue()
    {
        $payments = TransCicilanPayment::with(['kontrak.customer', 'kontrak.agen'])
            ->where('status', 'pending')
            ->whereDate('due_date', '<=', now()->toDateString())
            ->orderBy('due_date')
            ->get();

        return view('admin.trans_cicilan_payment.overdue', compact('payments'));
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

    public function notifyOverdue(TransCicilanPayment $payment)
    {
        $contract = $payment->kontrak;
        $customer = $contract ? $contract->customer : null;
        $phone = $customer ? preg_replace('/\D+/', '', (string) $customer->phone_wa) : '';
        if ($phone === '') {
            return back()->with('error', 'Nomor WhatsApp customer tidak tersedia.');
        }
        if (strpos($phone, '+') === 0) { $phone = ltrim($phone, '+'); }
        if (strpos($phone, '0') === 0) { $phone = '62' . substr($phone, 1); }
        $due = $payment->due_date ? $payment->due_date->format('d M Y') : '-';
        $amount = number_format((float) $payment->amount_due, 2, ',', '.');
        $name = $customer ? ($customer->full_name ?? 'Customer') : 'Customer';
        $kode = $contract ? ($contract->kode_kontrak ?? '-') : '-';
        $message = 'Assalamu’alaikum ' . $name . ",\n" .
            'Kami dari Jajan Emas ingin menginformasikan bahwa cicilan emas Anda untuk kontrak ' . $kode . ' (cicilan ke-' . (int) $payment->cicilan_ke . ') telah jatuh tempo pada ' . $due . ".\n" .
            'Jumlah tagihan: Rp ' . $amount . ".\n\n" .
            'Mohon konfirmasi pembayaran atau hubungi kami bila membutuhkan bantuan. Terima kasih.';
        $text = rawurlencode($message);
        $url = 'https://wa.me/' . $phone . '?text=' . $text;
        return redirect()->away($url);
    }
}