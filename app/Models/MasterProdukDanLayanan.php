<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterProdukDanLayanan extends Model
{
    use HasFactory;

    protected $table = 'master_produk_dan_layanan';

    protected $fillable = [
        'id_gramasi',
        'harga_hariini',
        'is_allow_ready',
        'is_allow_po',
        'harga_jasa',
        'image_produk',
        'expired_dae',
        'urutan',
        'status',
    ];

    protected $casts = [
        'id_gramasi'     => 'integer',
        'harga_hariini'  => 'decimal:2',
        'is_allow_ready' => 'boolean',
        'is_allow_po'    => 'boolean',
        'harga_jasa'     => 'decimal:2',
        'expired_dae'    => 'date',
    ];

    public function gramasi()
    {
        return $this->belongsTo(MasterGramasiEmas::class, 'id_gramasi');
    }
}