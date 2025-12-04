<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransReadyLog extends Model
{
    use HasFactory;

    protected $table = 'trans_ready_log';

    protected $fillable = [
        'trans_ready_id',
        'status',
        'description',
    ];

    public function ready()
    {
        return $this->belongsTo(TransReady::class, 'trans_ready_id');
    }
}