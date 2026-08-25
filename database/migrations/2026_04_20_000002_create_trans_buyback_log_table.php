<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_buyback_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_buyback_id');
            $table->enum('status', [
                'pending_review',
                'priced',
                'approved',
                'paid',
                'completed',
                'rejected',
                'cancelled',
            ]);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('trans_buyback_id')
                ->references('id')->on('trans_buyback')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_buyback_log');
    }
};
