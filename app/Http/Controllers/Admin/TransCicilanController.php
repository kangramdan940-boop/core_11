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
}