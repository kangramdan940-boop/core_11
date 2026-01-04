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
        'nama_produk',
        'images',
        'video_url',
        'deskripsi_pengiriman',
        'jumlah_terjual',
        'acara',
        'negara_asal',
        'is_custom',
        'is_mystery_box',
        'tags',
    ];

    protected $casts = [
        'gramasi'            => 'decimal:3',
        'harga_beli'         => 'decimal:2',
        'harga_jual_minimal' => 'decimal:2',
        'harga_jual_fix'     => 'decimal:2',
        'tahun_cetak'        => 'integer',
        'is_active'          => 'boolean',
        'images'             => 'array',
        'jumlah_terjual'     => 'integer',
        'is_custom'          => 'boolean',
        'is_mystery_box'     => 'boolean',
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
