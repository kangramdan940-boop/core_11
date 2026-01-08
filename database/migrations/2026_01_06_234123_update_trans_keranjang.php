<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('id_alamat_pengiriman');
            $table->enum('status_kadaluarsa', ['active','expired'])->default('active')->after('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'status_kadaluarsa']);
        });
    }
};