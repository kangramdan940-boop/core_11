<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            if (!Schema::hasColumn('trans_keranjang', 'resi_ekspedisi')) {
                $table->string('resi_ekspedisi', 150)->nullable()->after('status_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            if (Schema::hasColumn('trans_keranjang', 'resi_ekspedisi')) {
                $table->dropColumn('resi_ekspedisi');
            }
        });
    }
};