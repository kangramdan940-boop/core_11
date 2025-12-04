<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransPaymentLog extends Model
{
    use HasFactory;

    protected $table = 'trans_payment_log';

    protected $fillable = [
        'ref_type',
        'ref_id',
        'kode_payment',
        'amount',
        'currency',
        'payment_method',
        'provider',
        'payment_channel',
        'status',
        'paid_at',
        'failed_at',
        'expired_at',
        'refunded_at',
        'request_payload',
        'response_payload',
        'error_message',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'paid_at'    => 'datetime',
        'failed_at'  => 'datetime',
        'expired_at' => 'datetime',
        'refunded_at'=> 'datetime',
    ];
}
