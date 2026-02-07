<?php

namespace App\Observers;

use App\Models\TransPo;
use App\Models\TransPoLog;
use App\Models\SysNotification;

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

            $sysUserId = optional($po->customer)->sys_user_id;
            if ($sysUserId) {
                SysNotification::create([
                    'sys_user_id' => (int) $sysUserId,
                    'channel'     => 'system',
                    'ref_type'    => 'po',
                    'ref_id'      => (int) $po->id,
                    'title'       => 'Perubahan status transaksi',
                    'message'     => 'PO '.$po->kode_po.' berubah menjadi '.$po->status,
                    'data_json'   => json_encode([
                        'kode'   => $po->kode_po,
                        'status' => $po->status,
                    ]),
                    'status'      => 'sent',
                    'sent_at'     => now(),
                    'is_read'     => false,
                ]);
            }
        }
    }
}
