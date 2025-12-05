<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_produk_dan_layanan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_gramasi');
            $table->decimal('harga_hariini', 15, 2);
            $table->boolean('is_allow_ready')->default(true);
            $table->boolean('is_allow_po')->default(true);
            $table->decimal('harga_jasa', 15, 2)->nullable();
            $table->string('image_produk', 255)->nullable();
            $table->date('expired_dae')->nullable();
            $table->string('status', 30)->default('active');
            $table->integer('urutan')->nullable();

            $table->timestamps();

            $table->foreign('id_gramasi')
                ->references('id')
                ->on('master_gramasi_emas')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_produk_dan_layanan');
    }
};