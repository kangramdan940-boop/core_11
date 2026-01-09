<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_pembayaran_komisi', function (Blueprint $table) {
            $table->id();

            // Relasi ke mitra
            $table->unsignedBigInteger('id_mitra')->nullable();
            $table->index('id_mitra');

            // Relasi ke faktur (unik: 1 faktur = 1 komisi)
            $table->unsignedBigInteger('id_faktur')->unique();

            // Nilai pembayaran dan komisi
            $table->decimal('harga_yang_dibayar', 15, 2)->default(0);
            $table->decimal('total_komisi', 15, 2)->default(0);

            // Tanggal transaksi komisi
            $table->date('tanggal');

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->index('created_by');

            // Struk/berkas
            $table->string('file_struk_pembayaran', 255)->nullable();
            $table->string('file_struk_komisi', 255)->nullable();

            $table->timestamps();

            // Foreign keys mengikuti konvensi tabel yang ada
            $table->foreign('id_mitra')
                ->references('id')->on('master_mitra_brankas')
                ->nullOnDelete();

            $table->foreign('id_faktur')
                ->references('id')->on('gold_stock_documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_pembayaran_komisi');
    }
};