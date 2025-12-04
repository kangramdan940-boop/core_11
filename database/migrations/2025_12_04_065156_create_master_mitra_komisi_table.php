<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_mitra_komisi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('master_mitra_brankas_id')->nullable();

            // tipe komisi, bisa dikembangkan nanti:
            // po = pre-order fisik via akun brankas
            // cicilan, ready, dll kalau mau
            $table->string('tipe_transaksi', 30)->default('po');

            // komisi per gram (%)
            $table->decimal('komisi_persen', 5, 2); // contoh: 0.50 = 0.5%

            // periode berlaku (boleh null = berlaku terus)
            $table->date('berlaku_mulai')->nullable();
            $table->date('berlaku_sampai')->nullable();

            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->foreign('master_mitra_brankas_id')
                ->references('id')
                ->on('master_mitra_brankas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_mitra_komisi');
    }
};
