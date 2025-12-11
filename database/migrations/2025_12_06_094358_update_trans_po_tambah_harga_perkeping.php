<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->decimal('harga_per_keping', 15, 2)->nullable()->after('harga_per_gram');
        });
    }

    public function down(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->dropColumn('harga_per_keping');
        });
    }
};