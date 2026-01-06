<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_keranjang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_keranjang', 50)->unique();
            $table->decimal('ongkos_kirim', 15, 2)->default(0);
            $table->unsignedBigInteger('id_alamat_pengiriman');
            $table->timestamps();

            $table->foreign('id_alamat_pengiriman')
                ->references('id')->on('master_customer_address')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_keranjang');
    }
};