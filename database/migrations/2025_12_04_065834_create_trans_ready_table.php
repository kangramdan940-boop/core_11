<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_ready', function (Blueprint $table) {
            $table->id();

            $table->string('kode_trans', 50)->unique();

            $table->unsignedBigInteger('master_customer_id');
            $table->unsignedBigInteger('master_agen_id')->nullable();
            $table->unsignedBigInteger('master_gold_ready_stock_id'); // 1 transaksi 1 item

            $table->unsignedInteger('qty')->default(1);

            $table->decimal('harga_jual_satuan', 15, 2);
            $table->decimal('total_amount', 15, 2);

            $table->enum('status', [
                'pending_payment',
                'paid',
                'waiting_shipment',
                'shipped',
                'completed',
                'cancelled',
            ])->default('pending_payment');

            $table->enum('delivery_type', ['ship', 'pickup'])->default('ship');

            // data pengiriman
            $table->string('shipping_name', 150)->nullable();
            $table->string('shipping_phone', 30)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_province', 100)->nullable();
            $table->string('shipping_postal_code', 10)->nullable();

            // timeline
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // info payment singkat
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_reference', 100)->nullable();

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
        Schema::dropIfExists('trans_ready');
    }
};
