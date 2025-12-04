<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterBrandEmas extends Model
{
    use HasFactory;

    protected $table = 'master_brand_emas';

    protected $fillable = [
        'kode_brand',
        'nama_brand',
        'image_url',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}