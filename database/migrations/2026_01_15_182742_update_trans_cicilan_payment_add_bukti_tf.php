<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_cicilan_payment', function (Blueprint $table) {
            if (!Schema::hasColumn('trans_cicilan_payment', 'bukti_transfer')) {
                $table->string('bukti_transfer')->nullable()->after('payment_reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trans_cicilan_payment', function (Blueprint $table) {
            if (Schema::hasColumn('trans_cicilan_payment', 'bukti_transfer')) {
                $table->dropColumn('bukti_transfer');
            }
        });
    }
};