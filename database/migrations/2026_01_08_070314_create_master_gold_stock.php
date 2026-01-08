<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_gold_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_mitra_brankas_id')->nullable();
            $table->decimal('gramasi', 8, 3)->default(0);
            $table->unsignedInteger('qty')->default(0);
            $table->string('no_faktur', 100)->nullable();
            $table->text('uraian')->nullable();
            $table->decimal('berat', 10, 3)->default(0);
            $table->decimal('harga', 12, 2)->default(0);
            $table->string('file_faktur_url')->nullable();
            $table->decimal('total_pembayaran', 14, 2)->default(0);

            $table->decimal('uang_modal_mitra', 14, 2)->default(0);
            $table->decimal('uang_ganti_jajan_emas', 14, 2)->default(0);
            $table->decimal('uang_komisi_mitra', 14, 2)->default(0);
            $table->decimal('total_komisi', 14, 2)->default(0);
            $table->string('struk_komisi_url')->nullable();
            $table->string('struk_bayar_mitra_url')->nullable();
            $table->string('status_pengambilan', 30)->default('belum_diambil');

            $table->timestamps();

            $table->foreign('master_mitra_brankas_id')
                ->references('id')->on('master_mitra_brankas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_gold_stock');
    }
};