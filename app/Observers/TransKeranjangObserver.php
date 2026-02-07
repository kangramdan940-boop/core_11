<?php

namespace App\Observers;

use App\Models\TransKeranjang;
use App\Models\SysNotification;

class TransKeranjangObserver
{
    public function updated(TransKeranjang $k)
    {
        if ($k->wasChanged('status_order')) {
            $sysUserId = (int) ($k->created_by ?? 0);
            if ($sysUserId > 0) {
                SysNotification::create([
                    'sys_user_id' => $sysUserId,
                    'channel'     => 'system',
                    'ref_type'    => 'keranjang',
                    'ref_id'      => (int) $k->id,
                    'title'       => 'Perubahan status keranjang',
                    'message'     => 'Keranjang ' . $k->kode_keranjang . ' berubah menjadi ' . $k->status_order,
                    'data_json'   => json_encode([
                        'kode'   => $k->kode_keranjang,
                        'status' => $k->status_order,
                    ]),
                    'status'      => 'sent',
                    'sent_at'     => now(),
                    'is_read'     => false,
                ]);
            }
        }
    }
}