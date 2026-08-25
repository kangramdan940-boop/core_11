<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransBuyback extends Model
{
    use HasFactory;

    protected $table = 'trans_buyback';

    protected $fillable = [
        'kode_trans',
        'master_customer_id',
        'etalase_buyback_id',
        'brand',
        'berat_gram',
        'qty',
        'kondisi',
        'ada_sertifikat',
        'harga_buyback_estimasi',
        'harga_buyback_final',
        'total_amount',
        'status',
        'metode_serah',
        'resi_pengiriman',
        'bank_nama',
        'rekening_nomor',
        'rekening_atas_nama',
        'bukti_transfer_path',
        'submitted_at',
        'verified_at',
        'approved_at',
        'paid_at',
        'completed_at',
        'cancelled_at',
        'catatan',
        'catatan_admin',
    ];

    protected $casts = [
        'berat_gram'             => 'decimal:3',
        'qty'                    => 'integer',
        'ada_sertifikat'         => 'boolean',
        'harga_buyback_estimasi' => 'decimal:2',
        'harga_buyback_final'    => 'decimal:2',
        'total_amount'           => 'decimal:2',
        'submitted_at'           => 'datetime',
        'verified_at'            => 'datetime',
        'approved_at'            => 'datetime',
        'paid_at'                => 'datetime',
        'completed_at'           => 'datetime',
        'cancelled_at'           => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(MasterCustomer::class, 'master_customer_id');
    }

    public function logs()
    {
        return $this->hasMany(TransBuybackLog::class, 'trans_buyback_id');
    }

    public static function calculateAmount(float $hargaSatuan, int $qty): float
    {
        $amount = $hargaSatuan * $qty;
        return (float) number_format($amount, 2, '.', '');
    }

    public static function generateKodeTrans(): string
    {
        return 'BUYBACK-' . date('Ymd-His') . '-' . mt_rand(100, 999);
    }

    /**
     * Susun atribut untuk pengajuan buyback baru (status pending_review).
     */
    public static function buildAttributesForDraft(
        int $customerId,
        ?int $etalaseBuybackId,
        ?string $brand,
        float $beratGram,
        int $qty,
        float $hargaBuybackEstimasi,
        string $metodeSerah = 'datang_ke_lokasi',
        ?string $kondisi = null,
        bool $adaSertifikat = false,
        array $rekening = [],
        ?string $catatan = null
    ): array {
        $totalEstimasi = self::calculateAmount($hargaBuybackEstimasi, $qty);

        return [
            'kode_trans'             => self::generateKodeTrans(),
            'master_customer_id'     => $customerId,
            'etalase_buyback_id'     => $etalaseBuybackId,
            'brand'                  => $brand,
            'berat_gram'             => $beratGram,
            'qty'                    => $qty,
            'kondisi'                => $kondisi,
            'ada_sertifikat'         => $adaSertifikat,
            'harga_buyback_estimasi' => $hargaBuybackEstimasi,
            'harga_buyback_final'    => null,
            'total_amount'           => $totalEstimasi, // sementara pakai estimasi; final diisi admin
            'status'                 => 'pending_review',
            'metode_serah'           => $metodeSerah,
            'bank_nama'              => $rekening['bank_nama'] ?? null,
            'rekening_nomor'         => $rekening['rekening_nomor'] ?? null,
            'rekening_atas_nama'     => $rekening['rekening_atas_nama'] ?? null,
            'submitted_at'           => now(),
            'catatan'                => $catatan,
        ];
    }
}
