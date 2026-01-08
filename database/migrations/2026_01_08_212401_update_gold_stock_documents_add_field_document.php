<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gold_stock_documents', function (Blueprint $table) {
            $table->string('pdf_url')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('gold_stock_documents', function (Blueprint $table) {
            $table->dropColumn('pdf_url');
        });
    }
};