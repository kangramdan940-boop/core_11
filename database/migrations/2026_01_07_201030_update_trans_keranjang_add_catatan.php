<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            if (!Schema::hasColumn('trans_keranjang', 'bukti_transfer_url')) {
                $table->string('bukti_transfer_url', 255)->nullable();
            }
            if (!Schema::hasColumn('trans_keranjang', 'nama_pengirim')) {
                $table->string('nama_pengirim', 150)->nullable();
            }
            if (!Schema::hasColumn('trans_keranjang', 'nominal_transfer')) {
                $table->decimal('nominal_transfer', 15, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('trans_keranjang', function (Blueprint $table) {
            if (Schema::hasColumn('trans_keranjang', 'nominal_transfer')) {
                $table->dropColumn('nominal_transfer');
            }
            if (Schema::hasColumn('trans_keranjang', 'nama_pengirim')) {
                $table->dropColumn('nama_pengirim');
            }
            if (Schema::hasColumn('trans_keranjang', 'bukti_transfer_url')) {
                $table->dropColumn('bukti_transfer_url');
            }
        });
    }
};