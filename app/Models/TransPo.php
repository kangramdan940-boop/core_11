<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransPo extends Model
{
    use HasFactory;

    protected $table = 'trans_po';

    protected $fillable = [
        'kode_po',
        'master_customer_id',
        'master_agen_id',
        'id_master_produk_dan_layanan',
        'harga_per_gram',
        'total_gram',
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
        'processed_at',
        'ready_at_agen_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
        'payment_method',
        'payment_reference',
        'catatan',
        'estimasi_emas_diterima',
    ];

    protected $casts = [
        'harga_per_gram' => 'decimal:2',
        'total_gram'     => 'decimal:3',
        'total_amount'   => 'decimal:2',
        'ordered_at'     => 'datetime',
        'paid_at'        => 'datetime',
        'processed_at'   => 'datetime',
        'ready_at_agen_at' => 'datetime',
        'shipped_at'     => 'datetime',
        'completed_at'   => 'datetime',
        'cancelled_at'   => 'datetime',
        'estimasi_emas_diterima' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(MasterCustomer::class, 'master_customer_id');
    }

    public function agen()
    {
        return $this->belongsTo(MasterAgen::class, 'master_agen_id');
    }

    public function produk()
    {
        return $this->belongsTo(MasterProdukDanLayanan::class, 'id_master_produk_dan_layanan');
    }

    public static function calculateAmount(float $hargaPerGram, float $jasa): float
    {
        $amount = $hargaPerGram + $jasa + mt_rand(100, 999);
        return (float) number_format($amount, 2, '.', '');
    }

    public static function generateKodePo(): string
    {
        return 'PO-' . date('Ymd-His') . '-' . mt_rand(100, 999);
    }

    public static function buildAttributesForDraft(
        int $customerId,
        ?int $agenId,
        ?int $produkId,
        float $hargaPerGram,
        float $jasa,
        float $totalGram,
        string $deliveryType = 'ship',
        array $shipping = [],
        ?string $catatan = null
    ): array {
        $totalAmount = self::calculateAmount($hargaPerGram, jasa: $jasa);

        return [
            'kode_po'                     => self::generateKodePo(),
            'master_customer_id'          => $customerId,
            'master_agen_id'              => $agenId,
            'id_master_produk_dan_layanan'=> $produkId,
            'harga_per_gram'              => $hargaPerGram,
            'total_gram'                  => $totalGram,
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
