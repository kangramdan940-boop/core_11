<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFlashSale extends Model
{
    protected $table = 'master_flash_sales';

    protected $fillable = [
        'item_name',
        'harga_jual',
        'tahun',
        'periode',
        'harga_modal',
    ];

    protected $casts = [
        'harga_jual'  => 'decimal:2',
        'harga_modal' => 'decimal:2',
        'tahun'       => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\TransFlashSaleOrder::class, 'master_flash_sale_id');
    }
}