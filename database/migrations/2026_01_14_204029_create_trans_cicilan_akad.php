<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_cicilan_akad', function (Blueprint $table) {
            $table->id();

            // relasi utama ke kontrak cicilan dan pihak
            $table->unsignedBigInteger('trans_cicilan_emas_id');
            $table->unsignedBigInteger('master_customer_id')->nullable();
            $table->unsignedBigInteger('master_agen_id')->nullable();

            // identitas akad
            $table->string('nomor_akad', 50)->unique();
            $table->date('tanggal_akad')->nullable();
            $table->enum('akad_type', ['murabahah'])->default('murabahah');

            // pihak penjual (snapshot opsional, selain relasi agen)
            $table->enum('pihak_penjual_type', ['agen', 'perusahaan'])->default('agen');
            $table->string('penjual_nama', 150)->nullable();
            $table->string('penjual_alamat', 255)->nullable();

            // snapshot pokok akad (freeze nilai dari kontrak saat akad dibuat)
            $table->decimal('gramasi_total', 8, 3);
            $table->decimal('harga_per_gram_fix', 15, 2)->nullable();
            $table->decimal('harga_total_kontrak', 15, 2)->nullable();
            $table->unsignedTinyInteger('tenor_bulan')->nullable();
            $table->decimal('dp_amount', 15, 2)->nullable();
            $table->decimal('cicilan_per_bulan', 15, 2)->nullable();
            $table->decimal('margin_persen', 5, 2)->nullable();
            $table->decimal('margin_amount_total', 15, 2)->nullable();

            // status & tanda tangan
            $table->enum('status', [
                'draft',
                'signed_buyer',
                'signed_seller',
                'signed_both',
                'active',
                'cancelled',
            ])->default('draft');

            $table->timestamp('buyer_signed_at')->nullable();
            $table->timestamp('seller_signed_at')->nullable();
            $table->string('buyer_signature_url', 255)->nullable();
            $table->string('seller_signature_url', 255)->nullable();

            // dokumen & ketentuan
            $table->string('file_pdf_url', 255)->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->json('pasal_ketentuan')->nullable();

            $table->text('catatan')->nullable();
            $table->timestamps();

            // foreign keys
            $table->foreign('trans_cicilan_emas_id')
                ->references('id')->on('trans_cicilan_emas')
                ->cascadeOnDelete();

            $table->foreign('master_customer_id')
                ->references('id')->on('master_customer')
                ->cascadeOnDelete();

            $table->foreign('master_agen_id')
                ->references('id')->on('master_agen')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_cicilan_akad');
    }
};