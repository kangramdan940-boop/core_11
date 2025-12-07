<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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
        'is_edit',
        'sys_user_id',
    ];

    protected $casts = [
        'harian_limit_gram' => 'decimal:3',
        'komisi_persen'     => 'decimal:2',
        'is_active'         => 'boolean',
        'is_edit'           => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            if (empty($model->kode_mitra)) {
                $model->kode_mitra = 'MIT-' . strtoupper(Str::random(10));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sys_user_id');
    }
}
