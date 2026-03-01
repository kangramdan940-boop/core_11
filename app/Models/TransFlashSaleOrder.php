<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransFlashSaleOrder extends Model
{
    protected $table = 'trans_flash_sale_orders';

    protected $fillable = [
        'customer_name',
        'phone',
        'master_flash_sale_id',
        'shipping_address',
        'payment_proof_url',
        'package_proof_url',
        'qty',
        'pay_code',
        'created_by',
    ];

    public function flashSale()
    {
        return $this->belongsTo(\App\Models\MasterFlashSale::class, 'master_flash_sale_id');
    }
}