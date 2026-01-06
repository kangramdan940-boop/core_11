<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransKeranjang extends Model
{
    use HasFactory;

    protected $table = 'trans_keranjang';

    protected $fillable = [
        'kode_keranjang',
        'ongkos_kirim',
        'id_alamat_pengiriman',
    ];

    protected $casts = [
        'ongkos_kirim' => 'decimal:2',
    ];

    public function pos()
    {
        return $this->hasMany(TransPo::class, 'id_keranjang');
    }

    public function alamat()
    {
        return $this->belongsTo(\App\Models\MasterCustomerAddress::class, 'id_alamat_pengiriman');
    }
}