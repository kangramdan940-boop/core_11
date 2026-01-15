<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransCicilanAkad extends Model
{
    use HasFactory;

    protected $table = 'trans_cicilan_akad';

    protected $fillable = [
        'trans_cicilan_emas_id',
        'master_customer_id',
        'master_agen_id',
        'nomor_akad',
        'tanggal_akad',
        'akad_type',
        'pihak_penjual_type',
        'penjual_nama',
        'penjual_alamat',
        'gramasi_total',
        'harga_per_gram_fix',
        'harga_total_kontrak',
        'tenor_bulan',
        'dp_amount',
        'cicilan_per_bulan',
        'margin_persen',
        'margin_amount_total',
        'status',
        'buyer_signed_at',
        'seller_signed_at',
        'buyer_signature_url',
        'seller_signature_url',
        'file_pdf_url',
        'syarat_ketentuan',
        'pasal_ketentuan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_akad'        => 'date',
        'gramasi_total'       => 'decimal:3',
        'harga_per_gram_fix'  => 'decimal:2',
        'harga_total_kontrak' => 'decimal:2',
        'dp_amount'           => 'decimal:2',
        'cicilan_per_bulan'   => 'decimal:2',
        'margin_persen'       => 'decimal:2',
        'margin_amount_total' => 'decimal:2',
        'tenor_bulan'         => 'integer',
        'buyer_signed_at'     => 'datetime',
        'seller_signed_at'    => 'datetime',
        'pasal_ketentuan'     => 'array',
    ];

    public function kontrak()
    {
        return $this->belongsTo(\App\Models\TransCicilanEmas::class, 'trans_cicilan_emas_id');
    }



    public function agen()
    {
        return $this->belongsTo(\App\Models\MasterAgen::class, 'master_agen_id');
    }
}