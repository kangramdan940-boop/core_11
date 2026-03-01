<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trans_flash_sale_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->foreignId('master_flash_sale_id')->nullable()->constrained('master_flash_sales')->nullOnDelete();
            $table->text('shipping_address')->nullable();
            $table->string('payment_proof_url', 255)->nullable();
            $table->string('package_proof_url', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('trans_flash_sale_orders');
    }
};