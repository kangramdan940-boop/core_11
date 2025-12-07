<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_mitra_brankas', function (Blueprint $table) {
            $table->unsignedBigInteger('sys_user_id')->nullable()->after('email');
            $table->foreign('sys_user_id')
                ->references('id')->on('sys_user')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('master_mitra_brankas', function (Blueprint $table) {
            $table->dropForeign(['sys_user_id']);
            $table->dropColumn('sys_user_id');
        });
    }
};