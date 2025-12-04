<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_customer', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('email')->unique();
            $table->string('phone_wa', 30)->unique();

            $table->string('nik', 30)->nullable();
            $table->string('no_kk', 30)->nullable();
            $table->date('birth_date')->nullable();

            $table->string('address_line', 255)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable(); // KYC / verifikasi

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_customer');
    }
};
