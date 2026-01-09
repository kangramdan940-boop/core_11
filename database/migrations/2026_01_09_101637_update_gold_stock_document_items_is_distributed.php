<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gold_stock_documents', function (Blueprint $table) {
            $table->boolean('is_distributed')->default(false)->index()->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('gold_stock_documents', function (Blueprint $table) {
            $table->dropIndex(['is_distributed']);
            $table->dropColumn('is_distributed');
        });
    }
};