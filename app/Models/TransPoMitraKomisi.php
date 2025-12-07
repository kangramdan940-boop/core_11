<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransPoMitraKomisi extends Model
{
    use HasFactory;

    protected $table = 'trans_po_mitra_komisi';

    protected $fillable = [
        'trans_po_id',
        'master_mitra_brankas_id',
        'tanggal_komisi',
        'jumlah_gram',
        'komisi_persen',
        'komisi_amount',
        'catatan',
    ];

    protected $casts = [
        'tanggal_komisi' => 'date',
        'jumlah_gram'    => 'decimal:3',
        'komisi_persen'  => 'decimal:2',
        'komisi_amount'  => 'decimal:2',
    ];

    public function po()
    {
        return $this->belongsTo(TransPo::class, 'trans_po_id');
    }

    public function mitra()
    {
        return $this->belongsTo(MasterMitraBrankas::class, 'master_mitra_brankas_id');
    }

    public static function computeKomisiAmount(float $hargaPerGram, float $gram, float $persen): float
    {
        $amount = $hargaPerGram * $gram * ($persen / 100);
        return (float) number_format($amount, 2, '.', '');
    }
}