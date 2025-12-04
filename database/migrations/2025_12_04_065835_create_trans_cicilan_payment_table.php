<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_cicilan_payment', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trans_cicilan_id');

            $table->unsignedTinyInteger('cicilan_ke'); // 1..tenor

            $table->date('due_date')->nullable();
            $table->decimal('amount_due', 15, 2);

            $table->timestamp('paid_at')->nullable();
            $table->decimal('amount_paid', 15, 2)->default(0);

            // pending = belum bayar
            // process = sedang diproses
            // paid = sudah bayar
            // late = dibayar tapi lewat due date
            // refunded = dikembalikan
            $table->enum('status', ['pending','process', 'paid', 'late', 'refunded'])
                  ->default('pending');

            $table->string('payment_method', 50)->nullable();
            $table->string('payment_reference', 100)->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->foreign('trans_cicilan_id')
                ->references('id')->on('trans_cicilan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_cicilan_payment');
    }
};
