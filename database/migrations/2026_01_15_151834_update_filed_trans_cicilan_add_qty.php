<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_keping_diambil')
                ->default(0)
                ->after('gramasi');
        });
    }

    public function down(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->dropColumn('jumlah_keping_diambil');
        });
    }
};