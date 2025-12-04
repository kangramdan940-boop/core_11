<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_ready_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_ready_id');
            $table->enum('status', [
                'pending_payment',
                'paid',
                'shipped',
                'completed',
                'cancelled',
            ]);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('trans_ready_id')
                ->references('id')->on('trans_ready')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_ready_log');
    }
};