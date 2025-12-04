<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterAgen extends Model
{
    use HasFactory;

    protected $table = 'master_agen';

    protected $fillable = [
        'name',
        'email',
        'phone_wa',
        'kode_agen',
        'area',
        'address_line',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'master_agen_id');
    }
}
