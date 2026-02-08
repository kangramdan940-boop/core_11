<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterFaktur extends Model
{
    use HasFactory;

    protected $table = 'gold_stock_documents';

    protected $fillable = [
        'master_gold_stock_id',
        'issuer_company',
        'issuer_business_unit',
        'issuer_address',
        'issuer_website',
        'issuer_phone',
        'issuer_npwp',
        'issuer_npwp_holder',
        'issuer_npwp_address',
        'authorized_receiver_name',
        'authorized_receiver_nik',
        'invoice_number',
        'reference',
        'transaction_type',
        'date_raw',
        'date',
        'customer_name',
        'membership_number',
        'membership_tier',
        'service_name',
        'boutique_code_name',
        'boutique_location',
        'grand_total_idr',
        'dpp_idr',
        'ppn_rate',
        'ppn_idr',
        'currency',
        'payment_method',
        'virtual_account',
        'payment_no',
            'created_by',
            'print_by',
            'pdf_url',
            'raw_text',
            'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'grand_total_idr' => 'integer',
        'dpp_idr' => 'integer',
        'ppn_rate' => 'integer',
        'ppn_idr' => 'integer',
        'notes' => 'array',
        'is_distributed' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(\App\Models\MasterFakturItem::class, 'document_id');
    }

    public function stock()
    {
        return $this->belongsTo(\App\Models\MasterGoldStock::class, 'master_gold_stock_id');
    }
}