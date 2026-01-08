<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterFakturItem extends Model
{
    use HasFactory;

    protected $table = 'gold_stock_document_items';

    protected $fillable = [
        'document_id',
        'no',
        'description',
        'quantity_pcs',
        'weight_kg',
        'unit_price_idr',
        'total_idr',
    ];

    protected $casts = [
        'no' => 'integer',
        'quantity_pcs' => 'integer',
        'weight_kg' => 'decimal:6',
        'unit_price_idr' => 'integer',
        'total_idr' => 'integer',
    ];

    public function document()
    {
        return $this->belongsTo(\App\Models\MasterFaktur::class, 'document_id');
    }
}