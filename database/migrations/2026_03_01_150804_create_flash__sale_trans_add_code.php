<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_flash_sale_orders', function (Blueprint $table) {
            $table->unsignedSmallInteger('pay_code')->nullable()->after('qty');
            $table->index('pay_code');
        });
    }

    public function down(): void
    {
        Schema::table('trans_flash_sale_orders', function (Blueprint $table) {
            $table->dropIndex(['pay_code']);
            $table->dropColumn('pay_code');
        });
    }
};