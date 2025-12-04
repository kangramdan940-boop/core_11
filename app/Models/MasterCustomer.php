<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterCustomer extends Model
{
    use HasFactory;

    protected $table = 'master_customer';

    protected $fillable = [
        'full_name',
        'email',
        'phone_wa',
        'nik',
        'no_kk',
        'birth_date',
        'address_line',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'is_active',
        'verified_at',
        'sys_user_id',
    ];

    protected $casts = [
        'birth_date'  => 'date',
        'verified_at' => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sys_user_id');
    }
}
