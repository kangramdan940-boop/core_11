<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransCicilanEmas extends Model
{
    use HasFactory;

    protected $table = 'trans_cicilan_emas';

    protected $fillable = [
        'master_layanan_emas_cicilan_id',
        'master_agen_id',
        'master_gramasi_emas_id',
        'jumlah_keping_dibuka',
        'jumlah_keping_terpakai',
        'total_gram_dibuka',
    ];

    protected $casts = [
        'jumlah_keping_dibuka' => 'integer',
        'jumlah_keping_terpakai' => 'integer',
        'total_gram_dibuka' => 'decimal:3',
    ];

    public function layanan()
    {
        return $this->belongsTo(\App\Models\MasterLayananEmasCicilan::class, 'master_layanan_emas_cicilan_id');
    }

    public function agen()
    {
        return $this->belongsTo(\App\Models\MasterAgen::class, 'master_agen_id');
    }

    public function gramasi()
    {
        return $this->belongsTo(\App\Models\MasterGramasiEmas::class, 'master_gramasi_emas_id');
    }

    public function akads()
    {
        return $this->hasMany(\App\Models\TransCicilanAkad::class, 'trans_cicilan_emas_id');
    }

    public function latestAkad()
    {
        return $this->hasOne(\App\Models\TransCicilanAkad::class, 'trans_cicilan_emas_id')->latest('id');
    }
}