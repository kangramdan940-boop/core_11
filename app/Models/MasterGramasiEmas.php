<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterGramasiEmas extends Model
{
    use HasFactory;

    protected $table = 'master_gramasi_emas';

    protected $fillable = [
        'nama',
        'gramasi',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'gramasi'   => 'decimal:3',
        'is_active' => 'boolean',
    ];
}