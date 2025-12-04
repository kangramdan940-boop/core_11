<?php

namespace App\Observers;

use App\Models\TransReady;
use App\Models\TransReadyLog;

class TransReadyObserver
{
    public function created(TransReady $ready)
    {
        TransReadyLog::create([
            'trans_ready_id' => $ready->id,
            'status'         => $ready->status,
            'description'    => 'Transaksi ready dibuat pada '.now(),
        ]);
    }

    public function updated(TransReady $ready)
    {
        if ($ready->wasChanged('status')) {
            TransReadyLog::create([
                'trans_ready_id' => $ready->id,
                'status'         => $ready->status,
                'description'    => 'Status berubah menjadi '.$ready->status.' pada '.now(),
            ]);
        }
    }
}