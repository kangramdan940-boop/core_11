<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('trans_cicilan_akad', 'master_customer_id')) {
            Schema::table('trans_cicilan_akad', function (Blueprint $table) {
                $table->dropForeign(['master_customer_id']);
                $table->dropColumn('master_customer_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('trans_cicilan_akad', 'master_customer_id')) {
            Schema::table('trans_cicilan_akad', function (Blueprint $table) {
                $table->unsignedBigInteger('master_customer_id')->nullable()->after('trans_cicilan_emas_id');
                $table->foreign('master_customer_id')->references('id')->on('master_customer')->onDelete('set null');
            });
        }
    }
};