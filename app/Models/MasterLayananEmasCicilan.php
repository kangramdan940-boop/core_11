<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterLayananEmasCicilan extends Model
{
    use HasFactory;

    protected $table = 'master_layanan_emas_cicilan';

    protected $fillable = [
        'kode_layanan',
        'nama_layanan',
        'tenor_min_bulan',
        'tenor_max_bulan',
        'dp_min_persen',
        'dp_max_persen',
        'margin_persen',
        'margin_konfigurasi',
        'biaya_admin',
        'denda_terlambat_persen',
        'denda_terlambat_fixed',
        'grace_period_hari',
        'allowed_delivery_types',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'tenor_min_bulan' => 'integer',
        'tenor_max_bulan' => 'integer',
        'dp_min_persen' => 'decimal:2',
        'dp_max_persen' => 'decimal:2',
        'margin_persen' => 'decimal:2',
        'margin_konfigurasi' => 'array',
        'biaya_admin' => 'decimal:2',
        'denda_terlambat_persen' => 'decimal:2',
        'denda_terlambat_fixed' => 'decimal:2',
        'grace_period_hari' => 'integer',
        'allowed_delivery_types' => 'array',
        'is_active' => 'boolean',
    ];
}