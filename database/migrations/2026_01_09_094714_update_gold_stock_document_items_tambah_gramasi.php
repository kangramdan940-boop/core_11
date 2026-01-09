<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gold_stock_document_items', function (Blueprint $table) {
            $table->integer('gramasi')->nullable()->after('weight_kg');
        });
    }

    public function down(): void
    {
        Schema::table('gold_stock_document_items', function (Blueprint $table) {
            $table->dropColumn('gramasi');
        });
    }
};