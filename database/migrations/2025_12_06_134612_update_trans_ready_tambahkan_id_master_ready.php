<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            $table->unsignedBigInteger('id_master_gold_ready_stock')->nullable()->after('master_gold_ready_stock_id');
            $table->index('id_master_gold_ready_stock', 'idx_trans_ready_id_master_gold_ready_stock');
            $table->foreign('id_master_gold_ready_stock')
                  ->references('id')
                  ->on('master_gold_ready_stock')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            $table->dropForeign('trans_ready_id_master_gold_ready_stock_foreign');
            $table->dropIndex('idx_trans_ready_id_master_gold_ready_stock');
            $table->dropColumn('id_master_gold_ready_stock');
        });
    }
};