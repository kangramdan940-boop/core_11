<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterGoldReadyStock extends Model
{
    use HasFactory;

    protected $table = 'master_gold_ready_stock';

    protected $fillable = [
        'master_agen_id',
        'kode_item',
        'brand',
        'gramasi',
        'nomor_seri',
        'tahun_cetak',
        'kondisi_barang',
        'status',
        'harga_beli',
        'harga_jual_minimal',
        'harga_jual_fix',
        'lokasi_simpan',
        'catatan',
        'is_active',
    ];

    protected $casts = [
        'gramasi'            => 'decimal:3',
        'harga_beli'         => 'decimal:2',
        'harga_jual_minimal' => 'decimal:2',
        'harga_jual_fix'     => 'decimal:2',
        'tahun_cetak'        => 'integer',
        'is_active'          => 'boolean',
    ];

    public function agen()
    {
        return $this->belongsTo(MasterAgen::class, 'master_agen_id');
    }

    public function transReady()
    {
        return $this->hasMany(TransReady::class, 'master_gold_ready_stock_id');
    }

    public function kontrakCicilan()
    {
        return $this->hasMany(TransCicilan::class, 'master_gold_ready_stock_id');
    }
}
