<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPushToken extends Model
{
    protected $table = 'user_push_tokens';

    protected $fillable = [
        'sys_user_id',
        'expo_push_token',
        'device_name',
        'platform',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'last_used_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'sys_user_id');
    }
}
