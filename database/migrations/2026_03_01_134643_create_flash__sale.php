<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('master_flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('item_name', 150);
            $table->decimal('harga_jual', 12, 2);
            $table->integer('tahun')->nullable();
            $table->string('periode', 50)->nullable();
            $table->decimal('harga_modal', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('master_flash_sales');
    }
};