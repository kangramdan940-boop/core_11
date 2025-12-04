<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_brand_emas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_brand', 50)->unique();
            $table->string('nama_brand', 100)->unique();
            $table->string('image_url', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_brand_emas');
    }
};