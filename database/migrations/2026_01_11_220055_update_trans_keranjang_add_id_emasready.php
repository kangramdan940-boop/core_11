<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            if (!Schema::hasColumn('trans_po', 'master_gold_ready_stock_id')) {
                $table->unsignedBigInteger('master_gold_ready_stock_id')->nullable()->after('id_master_produk_dan_layanan');
                $table->foreign('master_gold_ready_stock_id')
                    ->references('id')->on('master_gold_ready_stock')
                    ->nullOnDelete();
                $table->unique('master_gold_ready_stock_id', 'uniq_trans_po_ready_stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            if (Schema::hasColumn('trans_po', 'master_gold_ready_stock_id')) {
                $table->dropUnique('uniq_trans_po_ready_stock');
                $table->dropForeign(['master_gold_ready_stock_id']);
                $table->dropColumn('master_gold_ready_stock_id');
            }
        });
    }
};