<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gold_prices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buy_price',
        'sell_price',
        'buyback_price',
        'source',
        'currency',
        'price_date',
        'last_updated',
        'is_active',
        'raw_response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'buyback_price' => 'decimal:2',
        'price_date' => 'datetime',
        'last_updated' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk mendapatkan harga aktif terbaru.
     */
    public function scopeLatestActive($query)
    {
        return $query->where('is_active', true)
                    ->orderBy('price_date', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk harga pada tanggal tertentu.
     */
    public function scopeOnDate($query, $date)
    {
        return $query->where('price_date', $date)
                    ->where('is_active', true);
    }

    /**
     * Format harga untuk ditampilkan.
     */
    public function getFormattedBuyPrice(): string
    {
        return 'Rp ' . number_format($this->buy_price, 0, ',', '.');
    }

    public function getFormattedSellPrice(): string
    {
        return 'Rp ' . number_format($this->sell_price, 0, ',', '.');
    }

    public function getFormattedBuybackPrice(): string
    {
        return 'Rp ' . number_format($this->buyback_price, 0, ',', '.');
    }
}