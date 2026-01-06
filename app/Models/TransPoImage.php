<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransPoImage extends Model
{
    use HasFactory;

    protected $table = 'trans_po_images';

    protected $fillable = [
        'trans_po_id',
        'file_path',
        'mime_type',
        'title',
        'size_bytes',
        'uploaded_by',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function po()
    {
        return $this->belongsTo(TransPo::class, 'trans_po_id');
    }
}