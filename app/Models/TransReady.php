<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransReady extends Model
{
    use HasFactory;

    protected $table = 'trans_ready';

    protected $fillable = [
        'kode_trans',
        'master_customer_id',
        'master_agen_id',
        'id_master_produk_dan_layanan',
        'master_gold_ready_stock_id',
        'qty',
        'harga_jual_satuan',
        'total_amount',
        'status',
        'delivery_type',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'ordered_at',
        'paid_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
        'payment_method',
        'payment_reference',
        'rekening_nomor',
        'catatan',
    ];

    protected $casts = [
        'harga_jual_satuan' => 'decimal:2',
        'total_amount'      => 'decimal:2',
        'ordered_at'        => 'datetime',
        'paid_at'           => 'datetime',
        'shipped_at'        => 'datetime',
        'completed_at'      => 'datetime',
        'cancelled_at'      => 'datetime',
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

    public function produk()
    {
        return $this->belongsTo(MasterProdukDanLayanan::class, 'id_master_produk_dan_layanan');
    }

    public static function calculateAmount(float $hargaJualSatuan, int $qty): float
    {
        $amount = $hargaJualSatuan * $qty;
        return (float) number_format($amount, 2, '.', '');
    }

    public static function generateKodeTrans(): string
    {
        return 'READY-' . date('Ymd-His') . '-' . mt_rand(100, 999);
    }

    public static function buildAttributesForDraft(
        int $customerId,
        ?int $agenId,
        ?int $produkId,
        int $readyStockId,
        int $qty,
        float $hargaJualSatuan,
        string $deliveryType = 'ship',
        array $shipping = [],
        ?string $catatan = null
    ): array {
        $totalAmount = self::calculateAmount($hargaJualSatuan, $qty);

        return [
            'kode_trans'                  => self::generateKodeTrans(),
            'master_customer_id'          => $customerId,
            'master_agen_id'              => $agenId,
            'id_master_produk_dan_layanan'=> $produkId,
            'master_gold_ready_stock_id'  => $readyStockId,
            'qty'                         => $qty,
            'harga_jual_satuan'           => $hargaJualSatuan,
            'total_amount'                => $totalAmount,
            'status'                      => 'pending_payment',
            'delivery_type'               => $deliveryType,
            'shipping_name'               => $shipping['name'] ?? null,
            'shipping_phone'              => $shipping['phone'] ?? null,
            'shipping_address'            => $shipping['address'] ?? null,
            'shipping_city'               => $shipping['city'] ?? null,
            'shipping_province'           => $shipping['province'] ?? null,
            'shipping_postal_code'        => $shipping['postal_code'] ?? null,
            'catatan'                     => $catatan,
        ];
    }
}
