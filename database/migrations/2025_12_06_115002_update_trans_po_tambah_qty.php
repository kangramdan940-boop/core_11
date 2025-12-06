<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->unsignedInteger('qty')->nullable()->after('total_gram');
        });
    }

    public function down(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
};