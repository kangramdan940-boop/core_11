<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            $table->unsignedBigInteger('id_master_produk_dan_layanan')->nullable()->after('master_agen_id');
            $table->foreign('id_master_produk_dan_layanan')
                ->references('id')->on('master_produk_dan_layanan')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            $table->dropForeign(['id_master_produk_dan_layanan']);
            $table->dropColumn('id_master_produk_dan_layanan');
        });
    }
};