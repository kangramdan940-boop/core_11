<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterPaymentSetting extends Model
{
    use HasFactory;

    protected $table = 'master_payment_settings';

    protected $fillable = [
        'rekening_nomor',
        'bank_nama',
        'rekening_atas_nama',
        'expired_minutes',
        'konfirmasi_petunjuk',
        'syarat_ketentuan',
        'jasa_titip_informasi',
    ];

    protected $casts = [
        'expired_minutes' => 'integer',
    ];
}