<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            if (!Schema::hasColumn('trans_ready', 'id_keranjang')) {
                $table->unsignedBigInteger('id_keranjang')->nullable()->after('master_agen_id');
                $table->foreign('id_keranjang')
                    ->references('id')->on('trans_keranjang')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            if (Schema::hasColumn('trans_ready', 'id_keranjang')) {
                $table->dropForeign(['id_keranjang']);
                $table->dropColumn('id_keranjang');
            }
        });
    }
};