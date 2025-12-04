<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_payment_log', function (Blueprint $table) {
            $table->id();

            // referensi ke transaksi
            // ref_type: po / ready / cicilan / cicilan_payment / other
            $table->string('ref_type', 30);
            $table->unsignedBigInteger('ref_id')->nullable();

            $table->string('kode_payment', 50)->unique();

            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('IDR');

            $table->string('payment_method', 50)->nullable();  // tf, va, qris, dll
            $table->string('provider', 50)->nullable();        // bank/gateway
            $table->string('payment_channel', 50)->nullable(); // optional

            // status pembayaran
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired',
                'refunded',
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->text('request_payload')->nullable();
            $table->text('response_payload')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_payment_log');
    }
};
