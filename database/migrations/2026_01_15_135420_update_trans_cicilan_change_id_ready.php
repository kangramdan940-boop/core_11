<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            if (!Schema::hasColumn('trans_cicilan', 'trans_cicilan_emas_id')) {
                $table->unsignedBigInteger('trans_cicilan_emas_id')->nullable()->after('master_gold_ready_stock_id');
            }
        });

        DB::statement('ALTER TABLE trans_cicilan MODIFY master_gold_ready_stock_id BIGINT UNSIGNED NULL');

        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->foreign('trans_cicilan_emas_id')
                ->references('id')->on('trans_cicilan_emas')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('trans_cicilan', function (Blueprint $table) {
            $table->dropForeign(['trans_cicilan_emas_id']);
            $table->dropColumn('trans_cicilan_emas_id');
        });

        DB::statement('ALTER TABLE trans_cicilan MODIFY master_gold_ready_stock_id BIGINT UNSIGNED NOT NULL');
    }
};