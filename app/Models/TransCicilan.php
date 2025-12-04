<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransCicilan extends Model
{
    use HasFactory;

    protected $table = 'trans_cicilan';

    protected $fillable = [
        'kode_kontrak',
        'master_customer_id',
        'master_agen_id',
        'master_gold_ready_stock_id',
        'gramasi',
        'harga_per_gram_fix',
        'harga_total_kontrak',
        'tenor_bulan',
        'dp_persen',
        'dp_amount',
        'dp_paid_at',
        'cicilan_per_bulan',
        'margin_persen',
        'margin_amount_total',
        'jumlah_cicilan_terbayar',
        'total_sudah_dibayar',
        'sisa_tagihan',
        'status',
        'mulai_kontrak',
        'jatuh_tempo_kontrak',
        'completed_at',
        'cancelled_at',
        'last_paid_at',
        'delivery_type',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'shipped_at',
        'received_at',
        'catatan',
    ];

    protected $casts = [
        'gramasi'             => 'decimal:3',
        'harga_per_gram_fix'  => 'decimal:2',
        'harga_total_kontrak' => 'decimal:2',
        'dp_persen'           => 'decimal:2',
        'dp_amount'           => 'decimal:2',
        'cicilan_per_bulan'   => 'decimal:2',
        'margin_persen'       => 'decimal:2',
        'margin_amount_total' => 'decimal:2',
        'total_sudah_dibayar' => 'decimal:2',
        'sisa_tagihan'        => 'decimal:2',
        'dp_paid_at'          => 'datetime',
        'mulai_kontrak'       => 'date',
        'jatuh_tempo_kontrak' => 'date',
        'completed_at'        => 'datetime',
        'cancelled_at'        => 'datetime',
        'last_paid_at'        => 'datetime',
        'shipped_at'          => 'datetime',
        'received_at'         => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(MasterCustomer::class, 'master_customer_id');
    }

    public function agen()
    {
        return $this->belongsTo(MasterAgen::class, 'master_agen_id');
    }

    public function readyStock()
    {
        return $this->belongsTo(MasterGoldReadyStock::class, 'master_gold_ready_stock_id');
    }

    public function cicilanPayments()
    {
        return $this->hasMany(TransCicilanPayment::class, 'trans_cicilan_id');
    }
}
