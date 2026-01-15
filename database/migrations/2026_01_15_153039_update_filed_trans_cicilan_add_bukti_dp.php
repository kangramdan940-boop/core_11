<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->string('file_bukti_bayar_dp')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->dropColumn('file_bukti_bayar_dp');
        });
    }
};