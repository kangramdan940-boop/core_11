<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('floating_price_and_buyback', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('brand')->nullable;
            $table->integer('harga')->nullable;
            $table->integer('buyback')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floating_price_and_buyback');
    }
};
