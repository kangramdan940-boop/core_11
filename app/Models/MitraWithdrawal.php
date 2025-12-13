<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MitraWithdrawal extends Model
{
    use HasFactory;

    public const STATUS_REQUESTED  = 'requested';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_CHECKING   = 'checking';
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_CANCELED   = 'canceled';

    protected $table = 'mitra_withdrawals';

    protected $fillable = [
        'master_mitra_brankas_id',
        'amount',
        'status',
        'target_account_no',
        'requested_at',
        'processed_at',
        'completed_at',
        'admin_notes',
        'payment_proof_url',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'requested_at'  => 'datetime',
        'processed_at'  => 'datetime',
        'completed_at'  => 'datetime',
    ];

    public function mitra()
    {
        return $this->belongsTo(MasterMitraBrankas::class, 'master_mitra_brankas_id');
    }
}