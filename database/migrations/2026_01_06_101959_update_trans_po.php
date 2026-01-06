<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->string('resi_number', 100)->nullable()->after('shipping_postal_code');
            $table->string('resi_courier', 100)->nullable()->after('resi_number');
            $table->string('resi_service', 100)->nullable()->after('resi_courier');
        });
    }

    public function down(): void {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->dropColumn(['resi_number', 'resi_courier', 'resi_service']);
        });
    }
};