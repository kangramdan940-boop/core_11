<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->unsignedBigInteger('master_layanan_emas_cicilan_id')
                  ->nullable()
                  ->after('master_agen_id');

            $table->foreign('master_layanan_emas_cicilan_id')
                  ->references('id')
                  ->on('master_layanan_emas_cicilan')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->dropForeign(['master_layanan_emas_cicilan_id']);
            $table->dropColumn('master_layanan_emas_cicilan_id');
        });
    }
};