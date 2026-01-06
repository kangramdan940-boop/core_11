<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->unsignedBigInteger('id_keranjang')->nullable()->after('master_agen_id');
            $table->foreign('id_keranjang')
                ->references('id')->on('trans_keranjang')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->dropForeign(['id_keranjang']);
            $table->dropColumn('id_keranjang');
        });
    }
};