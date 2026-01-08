<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterCustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'master_customer_address';

    protected $fillable = [
        'sys_user_id',
        'name',
        'phone',
        'lines',
        'city',
        'tag',
        'shipping_cost',
    ];

    protected $casts = [
        'lines' => 'array',
        'shipping_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sys_user_id');
    }
}