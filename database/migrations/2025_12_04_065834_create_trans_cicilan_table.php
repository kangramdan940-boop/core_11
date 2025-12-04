<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_cicilan', function (Blueprint $table) {
            $table->id();

            $table->string('kode_kontrak', 50)->unique();

            $table->unsignedBigInteger('master_customer_id');
            $table->unsignedBigInteger('master_agen_id')->nullable();
            $table->unsignedBigInteger('master_gold_ready_stock_id'); // emas dikunci dari awal

            // info emas & harga
            $table->decimal('gramasi', 8, 3);
            $table->decimal('harga_per_gram_fix', 15, 2);
            $table->decimal('harga_total_kontrak', 15, 2); // harga fix di awal (include margin)

            // tenor & DP
            $table->unsignedTinyInteger('tenor_bulan'); // 3–12
            $table->decimal('dp_persen', 5, 2)->default(20.00);
            $table->decimal('dp_amount', 15, 2)->default(0);
            $table->timestamp('dp_paid_at')->nullable();

            // cicilan
            $table->decimal('cicilan_per_bulan', 15, 2);
            $table->decimal('margin_persen', 5, 2)->nullable();
            $table->decimal('margin_amount_total', 15, 2)->nullable();

            // progress pembayaran
            $table->unsignedTinyInteger('jumlah_cicilan_terbayar')->default(0);
            $table->decimal('total_sudah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_tagihan', 15, 2)->default(0);

            // status kontrak
            // active = cicilan berjalan
            // completed = lunas
            // cancelled = dibatalkan sebelum lunas (uang dikembalikan)
            // defaulted = gagal bayar (kalau nanti kebijakan berubah)
            $table->enum('status', [
                'active',
                'completed',
                'cancelled',
                'defaulted',
            ])->default('active');

            $table->date('mulai_kontrak')->nullable();
            $table->date('jatuh_tempo_kontrak')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('last_paid_at')->nullable();

            // pengiriman emas setelah lunas
            $table->enum('delivery_type', ['ship', 'pickup'])->default('ship');
            $table->string('shipping_name', 150)->nullable();
            $table->string('shipping_phone', 30)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_province', 100)->nullable();
            $table->string('shipping_postal_code', 10)->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('received_at')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->foreign('master_customer_id')
                ->references('id')->on('master_customer')
                ->cascadeOnDelete();

            $table->foreign('master_agen_id')
                ->references('id')->on('master_agen')
                ->nullOnDelete();

            $table->foreign('master_gold_ready_stock_id')
                ->references('id')->on('master_gold_ready_stock')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_cicilan');
    }
};
