<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterGoldPrice extends Model
{
    use HasFactory;

    protected $table = 'master_gold_price';

    protected $fillable = [
        'price_date',
        'source',
        'price_buy',
        'price_sell',
        'price_buyback',
        'note',
        'is_active',
    ];

    protected $casts = [
        'price_date'    => 'date',
        'price_buy'     => 'decimal:2',
        'price_sell'    => 'decimal:2',
        'price_buyback' => 'decimal:2',
        'is_active'     => 'boolean',
    ];
}
