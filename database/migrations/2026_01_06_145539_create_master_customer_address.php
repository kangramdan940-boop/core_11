<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_customer_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sys_user_id')->constrained('sys_user')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('phone', 50)->nullable();
            $table->json('lines');
            $table->string('city', 255)->nullable();
            $table->string('tag', 50)->nullable();
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_customer_address');
    }
};