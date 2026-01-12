<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            if (!Schema::hasColumn('trans_keranjang', 'customer_rating')) {
                $table->tinyInteger('customer_rating')->nullable()->after('resi_ekspedisi');
            }
            if (!Schema::hasColumn('trans_keranjang', 'customer_review')) {
                $table->text('customer_review')->nullable()->after('customer_rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            if (Schema::hasColumn('trans_keranjang', 'customer_rating')) {
                $table->dropColumn('customer_rating');
            }
            if (Schema::hasColumn('trans_keranjang', 'customer_review')) {
                $table->dropColumn('customer_review');
            }
        });
    }
};