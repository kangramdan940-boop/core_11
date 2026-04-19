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
        Schema::create('gold_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('buy_price', 12, 2)->comment('Harga beli emas per gram');
            $table->decimal('sell_price', 12, 2)->comment('Harga jual/buyback emas per gram');
            $table->decimal('buyback_price', 12, 2)->comment('Harga buyback khusus');
            $table->string('source')->default('HRTA Gold')->comment('Sumber data harga');
            $table->string('currency')->default('IDR')->comment('Mata uang');
            $table->timestamp('price_date')->useCurrent()->comment('Tanggal berlaku harga');
            $table->timestamp('last_updated')->useCurrent()->comment('Tanggal terakhir update dari sumber');
            $table->boolean('is_active')->default(true)->comment('Status aktif harga');
            $table->text('raw_response')->nullable()->comment('Respons mentah dari API');
            $table->timestamps();
            
            // Index untuk pencarian yang efisien
            $table->index('price_date');
            $table->index('is_active');
            $table->index(['price_date', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gold_prices');
    }
};