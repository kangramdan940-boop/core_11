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
            ->paginate(20);

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
}