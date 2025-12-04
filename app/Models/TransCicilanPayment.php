<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransCicilanPayment extends Model
{
    use HasFactory;

    protected $table = 'trans_cicilan_payment';

    protected $fillable = [
        'trans_cicilan_id',
        'cicilan_ke',
        'due_date',
        'amount_due',
        'paid_at',
        'amount_paid',
        'status',
        'payment_method',
        'payment_reference',
        'catatan',
    ];

    protected $casts = [
        'due_date'    => 'date',
        'amount_due'  => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'paid_at'     => 'datetime',
    ];

    public function kontrak()
    {
        return $this->belongsTo(TransCicilan::class, 'trans_cicilan_id');
    }
}
