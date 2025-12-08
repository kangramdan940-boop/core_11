<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            $table->string('rekening_nomor', 50)->nullable()->after('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::table('trans_ready', function (Blueprint $table) {
            $table->dropColumn('rekening_nomor');
        });
    }
};