<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            $table->enum('status_order', [
                'perlu_dibayar',
                'dikemas',
                'dikirim',
                'dibatalkan',
                'selesai',
            ])->default('perlu_dibayar')->after('status_kadaluarsa');
        });
    }

    public function down(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            $table->dropColumn('status_order');
        });
    }
};