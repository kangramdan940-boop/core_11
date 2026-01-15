<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_cicilan_emas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_layanan_emas_cicilan_id');
            $table->unsignedBigInteger('master_agen_id')->nullable();
            $table->unsignedBigInteger('master_gramasi_emas_id');
            $table->unsignedInteger('jumlah_keping_dibuka');
            $table->decimal('total_gram_dibuka', 8, 3);
            $table->timestamps();

            $table->foreign('master_layanan_emas_cicilan_id')
                ->references('id')->on('master_layanan_emas_cicilan')
                ->cascadeOnDelete();

            $table->foreign('master_agen_id')
                ->references('id')->on('master_agen')
                ->nullOnDelete();

            $table->foreign('master_gramasi_emas_id')
                ->references('id')->on('master_gramasi_emas')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_cicilan_emas');
    }
};