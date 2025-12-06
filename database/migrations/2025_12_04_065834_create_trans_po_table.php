<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_po', function (Blueprint $table) {
            $table->id();

            $table->string('kode_po', 50)->unique();

            $table->unsignedBigInteger('master_customer_id');
            $table->unsignedBigInteger('master_agen_id')->nullable(); // agen yang handle

            // snapshot harga saat akad
            $table->decimal('harga_per_gram', 15, 2);
            $table->decimal('harga_per_keping', 15, 2);
            $table->decimal('total_gram', 10, 3);
            $table->decimal('total_amount', 15, 2);

            // status utama PO
            // pending_payment = belum bayar
            // paid = sudah bayar, tunggu proses
            // processing = agen proses pembelian di brankas
            // ready_at_agen = emas fisik sudah di agen
            // shipped = dikirim
            // completed = selesai
            // cancelled = dibatalkan/refund
            $table->enum('status', [
                'pending_payment',
                'paid',
                'processing',
                'ready_at_agen',
                'shipped',
                'completed',
                'cancelled',
            ])->default('pending_payment');

            // tipe penerimaan: dikirim / ambil / titip di agen
            $table->enum('delivery_type', ['ship', 'pickup', 'titip_agen'])
                  ->default('ship');

            // data pengiriman (diisi kalau delivery_type = ship)
            $table->string('shipping_name', 150)->nullable();
            $table->string('shipping_phone', 30)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_province', 100)->nullable();
            $table->string('shipping_postal_code', 10)->nullable();

            // timeline penting
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('ready_at_agen_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // info payment ringkas (detail-nya di trans_payment_log)
            $table->string('payment_method', 50)->nullable(); // transfer, va, dll
            $table->string('payment_reference', 100)->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();

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
        Schema::dropIfExists('trans_po');
    }
};
