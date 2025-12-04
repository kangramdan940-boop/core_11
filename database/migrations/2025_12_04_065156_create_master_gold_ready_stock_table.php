<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
    {
        Schema::create('master_gold_ready_stock', function (Blueprint $table) {
            $table->id();

            // pemilik stok (agen)
            $table->unsignedBigInteger('master_agen_id')->nullable();

            // identitas emas
            $table->string('kode_item', 100)->unique(); // kode internal
            $table->string('brand', 50)->default('antam'); // antam / ubs / lainnya
            $table->decimal('gramasi', 8, 3); // misal 0.5, 1, 2, 5, dst
            $table->string('nomor_seri', 100)->nullable();
            $table->unsignedSmallInteger('tahun_cetak')->nullable();

            // kondisi fisik
            $table->enum('kondisi_barang', ['mint', 'second'])->default('mint');

            // status stok
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');

            // harga terkait stok ini (boleh beda-beda per item)
            $table->decimal('harga_beli', 15, 2)->nullable();       // berapa agen beli
            $table->decimal('harga_jual_minimal', 15, 2)->nullable(); // harga jual dasar
            $table->decimal('harga_jual_fix', 15, 2)->nullable();   // kalau mau lock harga item ini

            $table->string('lokasi_simpan', 150)->nullable(); // keterangan rak/brankas/box
            $table->text('catatan')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('master_agen_id')
                ->references('id')
                ->on('master_agen')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_gold_ready_stock');
    }
};
