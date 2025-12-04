<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterMitraBrankas extends Model
{
    use HasFactory;

    protected $table = 'master_mitra_brankas';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'phone_wa',
        'kode_mitra',
        'platform',
        'account_no',
        'harian_limit_gram',
        'komisi_persen',
        'is_active',
    ];

    protected $casts = [
        'harian_limit_gram' => 'decimal:3',
        'komisi_persen'     => 'decimal:2',
        'is_active'         => 'boolean',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'master_mitra_brankas_id');
    }
}
