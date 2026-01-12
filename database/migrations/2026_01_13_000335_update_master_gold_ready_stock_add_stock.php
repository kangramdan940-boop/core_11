<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_gold_ready_stock', function (Blueprint $table) {
            $table->integer('stok')->default(1)->after('jumlah_terjual');
        });
    }

    public function down(): void
    {
        Schema::table('master_gold_ready_stock', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
};