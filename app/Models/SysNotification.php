<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SysNotification extends Model
{
    use HasFactory;

    protected $table = 'sys_notification';

    protected $fillable = [
        'sys_user_id',
        'channel',
        'title',
        'message',
        'data_json',
        'status',
        'sent_at',
        'read_at',
        'error_message',
        'is_read',
    ];

    protected $casts = [
        'sent_at'  => 'datetime',
        'read_at'  => 'datetime',
        'is_read'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sys_user_id');
    }
}
