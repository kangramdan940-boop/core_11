<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Blameable;

class TransPoLog extends Model
{
    use Blameable;

    protected $table = 'trans_po_log';

    protected $fillable = [
        'trans_po_id',
        'status',
        'description',
        'created_by',
        'updated_by',
    ];
}