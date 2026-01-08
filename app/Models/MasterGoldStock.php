<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterGoldStock extends Model
{
    use HasFactory;

    protected $table = 'master_gold_stock';

    protected $fillable = [
        'master_mitra_brankas_id',
        'gramasi',
        'qty',
        'no_faktur',
        'uraian',
        'berat',
        'harga',
        'file_faktur_url',
        'total_pembayaran',
        'uang_modal_mitra',
        'uang_ganti_jajan_emas',
        'uang_komisi_mitra',
        'total_komisi',
        'struk_komisi_url',
        'struk_bayar_mitra_url',
        'status_pengambilan',
    ];

    protected $casts = [
        'gramasi'              => 'decimal:3',
        'qty'                  => 'integer',
        'berat'                => 'decimal:3',
        'harga'                => 'decimal:2',
        'total_pembayaran'     => 'decimal:2',
        'uang_modal_mitra'     => 'decimal:2',
        'uang_ganti_jajan_emas'=> 'decimal:2',
        'uang_komisi_mitra'    => 'decimal:2',
        'total_komisi'         => 'decimal:2',
    ];

    public function mitra()
    {
        return $this->belongsTo(\App\Models\MasterMitraBrankas::class, 'master_mitra_brankas_id');
    }
}