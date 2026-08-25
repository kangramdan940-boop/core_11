<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Blameable;

class TransBuybackLog extends Model
{
    use HasFactory, Blameable;

    protected $table = 'trans_buyback_log';

    protected $fillable = [
        'trans_buyback_id',
        'status',
        'description',
        'created_by',
        'updated_by',
    ];

    public function buyback()
    {
        return $this->belongsTo(TransBuyback::class, 'trans_buyback_id');
    }
}
