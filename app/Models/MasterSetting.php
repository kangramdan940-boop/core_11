<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterSetting extends Model
{
    use HasFactory;

    protected $table = 'master_setting';

    protected $fillable = [
        'key',
        'value',
        'label',
        'group',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
