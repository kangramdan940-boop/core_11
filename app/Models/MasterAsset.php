<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterAsset extends Model
{
    use HasFactory;

    protected $table = 'master_asset';

    protected $fillable = [
        'title',
        'type',
        'category',
        'url',
        'file_hash',
        'file_size',
        'file_extension',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}