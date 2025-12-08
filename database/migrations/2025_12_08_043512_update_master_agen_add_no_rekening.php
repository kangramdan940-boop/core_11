<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_agen', function (Blueprint $table) {
            $table->string('rekening_nomor', 50)->nullable()->after('address_line');
        });
    }

    public function down(): void
    {
        Schema::table('master_agen', function (Blueprint $table) {
            $table->dropColumn('rekening_nomor');
        });
    }
};