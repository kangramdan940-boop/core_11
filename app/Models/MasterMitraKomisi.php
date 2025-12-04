<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterMitraKomisi extends Model
{
    use HasFactory;

    protected $table = 'master_mitra_komisi';

    protected $fillable = [
        'master_mitra_brankas_id',
        'tipe_transaksi',
        'komisi_persen',
        'berlaku_mulai',
        'berlaku_sampai',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'komisi_persen'  => 'decimal:2',
        'berlaku_mulai'  => 'date',
        'berlaku_sampai' => 'date',
        'is_active'      => 'boolean',
    ];

    public function mitra()
    {
        return $this->belongsTo(MasterMitraBrankas::class, 'master_mitra_brankas_id');
    }
}
