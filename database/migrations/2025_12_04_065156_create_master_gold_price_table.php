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
        Schema::create('master_gold_price', function (Blueprint $table) {
            $table->id();

            $table->date('price_date'); // tanggal harga
            $table->string('source', 50)->default('global'); 
            // misal: global, antam, custom

            // harga per gram (IDR)
            $table->decimal('price_buy', 15, 2);      // harga modal / beli
            $table->decimal('price_sell', 15, 2);     // harga jual ke customer (default)
            $table->decimal('price_buyback', 15, 2)->nullable(); // kalau nanti mau buyback

            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['price_date', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_gold_price');
    }
};
