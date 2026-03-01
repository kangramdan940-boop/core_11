<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('trans_flash_sale_orders', function (Blueprint $table) {
            $table->unsignedInteger('qty')->nullable()->after('package_proof_url');
        });
    }

    public function down(): void {
        Schema::table('trans_flash_sale_orders', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
};