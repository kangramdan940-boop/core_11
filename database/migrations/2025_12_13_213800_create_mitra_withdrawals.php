<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mitra_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_mitra_brankas_id');
            $table->decimal('amount', 18, 2);
            $table->string('status', 30)->index(); // requested, processing, completed, canceled
            $table->string('target_account_no', 100)->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('master_mitra_brankas_id')
                ->references('id')->on('master_mitra_brankas')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_withdrawals');
    }
};