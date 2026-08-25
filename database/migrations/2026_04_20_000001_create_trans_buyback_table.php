<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_buyback', function (Blueprint $table) {
            $table->id();

            $table->string('kode_trans', 50)->unique();

            $table->unsignedBigInteger('master_customer_id');

            // referensi etalase buyback yang dipilih customer (opsional)
            $table->unsignedBigInteger('etalase_buyback_id')->nullable();

            // detail emas yang dijual customer
            $table->string('brand', 150)->nullable();
            $table->decimal('berat_gram', 12, 3)->default(0);
            $table->unsignedInteger('qty')->default(1);
            $table->string('kondisi', 100)->nullable();   // mis. mulus, ada lecet
            $table->boolean('ada_sertifikat')->default(false);

            // harga
            $table->decimal('harga_buyback_estimasi', 15, 2)->default(0); // per unit saat submit
            $table->decimal('harga_buyback_final', 15, 2)->nullable();    // per unit, ditetapkan admin
            $table->decimal('total_amount', 15, 2)->default(0);           // total yang ditransfer ke customer

            $table->enum('status', [
                'pending_review', // pengajuan baru, menunggu verifikasi fisik
                'priced',         // admin sudah menetapkan harga final, menunggu persetujuan customer
                'approved',       // customer menyetujui harga final
                'paid',           // dana sudah ditransfer ke customer
                'completed',      // selesai
                'rejected',       // ditolak admin (mis. emas tidak sesuai)
                'cancelled',      // dibatalkan customer
            ])->default('pending_review');

            // cara serah emas
            $table->enum('metode_serah', ['kirim', 'datang_ke_lokasi'])->default('datang_ke_lokasi');
            $table->string('resi_pengiriman', 100)->nullable();

            // rekening tujuan pencairan (milik customer)
            $table->string('bank_nama', 100)->nullable();
            $table->string('rekening_nomor', 100)->nullable();
            $table->string('rekening_atas_nama', 150)->nullable();

            // bukti transfer dari admin ke customer
            $table->string('bukti_transfer_path', 255)->nullable();

            // timeline
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->text('catatan')->nullable();       // catatan customer
            $table->text('catatan_admin')->nullable();  // catatan admin / alasan tolak

            $table->timestamps();

            $table->foreign('master_customer_id')
                ->references('id')->on('master_customer')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_buyback');
    }
};
