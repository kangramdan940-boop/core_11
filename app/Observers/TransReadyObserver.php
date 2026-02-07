<?php

namespace App\Observers;

use App\Models\TransReady;
use App\Models\TransReadyLog;
use App\Models\SysNotification;

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

            $sysUserId = optional($ready->customer)->sys_user_id;
            if ($sysUserId) {
                SysNotification::create([
                    'sys_user_id' => (int) $sysUserId,
                    'channel'     => 'system',
                    'ref_type'    => 'ready',
                    'ref_id'      => (int) $ready->id,
                    'title'       => 'Perubahan status transaksi',
                    'message'     => 'Transaksi READY '.$ready->kode_trans.' berubah menjadi '.$ready->status,
                    'data_json'   => json_encode([
                        'kode'   => $ready->kode_trans,
                        'status' => $ready->status,
                    ]),
                    'status'      => 'sent',
                    'sent_at'     => now(),
                    'is_read'     => false,
                ]);
            }
        }
    }
}