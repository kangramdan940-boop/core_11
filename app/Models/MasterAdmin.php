<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterAdmin extends Model
{
    use HasFactory;

    protected $table = 'master_admin';

    protected $fillable = [
        'name',
        'email',
        'phone_wa',
        'jabatan',
        'is_super_admin',
        'is_active',
    ];

    protected $casts = [
        'is_super_admin' => 'boolean',
        'is_active'      => 'boolean',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'master_admin_id');
    }
}
