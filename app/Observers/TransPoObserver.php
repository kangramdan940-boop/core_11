<?php

namespace App\Observers;

use App\Models\TransPo;
use App\Models\TransPoLog;

class TransPoObserver
{
    public function created(TransPo $po)
    {
        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'PO dibuat pada '.now(),
        ]);
    }

    public function updated(TransPo $po)
    {
        if ($po->wasChanged('status')) {
            TransPoLog::create([
                'trans_po_id' => $po->id,
                'status'      => $po->status,
                'description' => 'Status berubah menjadi '.$po->status.' pada '.now(),
            ]);
        }
    }
}
