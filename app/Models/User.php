<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, CanResetPassword;

    protected $table = 'sys_user';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // RELASI KE MASTER PROFILE
    public function customer()
    {
        return $this->hasOne(MasterCustomer::class, 'sys_user_id');
    }

    public function agen()
    {
        return $this->hasOne(MasterAgen::class, 'sys_user_id');
    }

    public function mitraBrankas()
    {
        return $this->hasOne(MasterMitraBrankas::class, 'sys_user_id');
    }

    public function adminProfile()
    {
        return $this->hasOne(MasterAdmin::class, 'sys_user_id');
    }

    public function notifications()
    {
        return $this->hasMany(SysNotification::class, 'sys_user_id');
    }
}
