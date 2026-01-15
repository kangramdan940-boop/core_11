<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_layanan_emas_cicilan', function (Blueprint $table) {
            $table->id();

            $table->string('kode_layanan', 50)->unique();
            $table->string('nama_layanan', 150);

            $table->unsignedTinyInteger('tenor_min_bulan');
            $table->unsignedTinyInteger('tenor_max_bulan');

            $table->decimal('dp_min_persen', 5, 2);
            $table->decimal('dp_max_persen', 5, 2);

            $table->decimal('margin_persen', 5, 2)->nullable();
            $table->json('margin_konfigurasi')->nullable();

            $table->decimal('biaya_admin', 15, 2)->default(0);

            $table->decimal('denda_terlambat_persen', 5, 2)->nullable();
            $table->decimal('denda_terlambat_fixed', 15, 2)->nullable();

            $table->unsignedTinyInteger('grace_period_hari')->default(3);

            $table->json('allowed_delivery_types')->nullable();

            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_layanan_emas_cicilan');
    }
};