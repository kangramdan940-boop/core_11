<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransPoMobilitas extends Model
{
    use HasFactory;

    protected $table = 'trans_po_mobilitas';

    protected $fillable = [
        'trans_po_id',
        'tanggal',
        'kategori',
        'deskripsi',
        'amount',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'amount'  => 'decimal:2',
    ];

    public function po()
    {
        return $this->belongsTo(TransPo::class, 'trans_po_id');
    }
}