<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Blameable;

class TransReadyLog extends Model
{
    use HasFactory, Blameable;

    protected $table = 'trans_ready_log';

    protected $fillable = [
        'trans_ready_id',
        'status',
        'description',
        'created_by',
        'updated_by',
    ];

    public function ready()
    {
        return $this->belongsTo(TransReady::class, 'trans_ready_id');
    }
}