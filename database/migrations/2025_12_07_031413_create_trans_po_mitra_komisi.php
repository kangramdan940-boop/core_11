<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_po_mitra_komisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_po_id');
            $table->unsignedBigInteger('master_mitra_brankas_id');
            $table->date('tanggal_komisi');
            $table->decimal('jumlah_gram', 10, 3);
            $table->decimal('komisi_persen', 5, 2);
            $table->decimal('komisi_amount', 15, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['trans_po_id'], 'idx_tpmk_trans_po_id');
            $table->index(['master_mitra_brankas_id', 'tanggal_komisi'], 'idx_tpmk_mitra_tanggal');

            $table->foreign('trans_po_id')
                ->references('id')->on('trans_po')
                ->cascadeOnDelete();

            $table->foreign('master_mitra_brankas_id')
                ->references('id')->on('master_mitra_brankas')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_po_mitra_komisi');
    }
};