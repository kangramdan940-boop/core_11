<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_gold_ready_stock', function (Blueprint $table) {
            $table->string('nama_produk')->nullable()->comment('Nama display produk');
            $table->json('images')->nullable()->comment('Array URL gambar');
            $table->text('video_url')->nullable()->comment('Link video TikTok/Youtube');
            $table->string('deskripsi_pengiriman', 255)->nullable();
            $table->integer('jumlah_terjual')->nullable();
            $table->string('acara', 100)->nullable();
            $table->string('negara_asal', 100)->nullable();
            $table->boolean('is_custom')->nullable();
            $table->boolean('is_mystery_box')->nullable();
            $table->text('tags')->nullable()->comment('Keyword gaya/style');
        });
    }

    public function down(): void
    {
        Schema::table('master_gold_ready_stock', function (Blueprint $table) {
            $table->dropColumn([
                'nama_produk',
                'images',
                'video_url',
                'deskripsi_pengiriman',
                'jumlah_terjual',
                'acara',
                'negara_asal',
                'is_custom',
                'is_mystery_box',
                'tags',
            ]);
        });
    }
};