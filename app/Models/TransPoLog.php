<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransPoLog extends Model
{
    protected $table = 'trans_po_log';

    protected $fillable = [
        'trans_po_id',
        'status',
        'description',
    ];
}