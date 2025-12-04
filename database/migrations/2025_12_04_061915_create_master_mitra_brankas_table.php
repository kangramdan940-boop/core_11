<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_mitra_brankas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->string('email')->unique();
            $table->string('phone_wa', 30)->unique();

            $table->string('kode_mitra', 50)->unique();
            $table->string('platform', 50)->default('brankas_antam');
            $table->string('account_no', 100)->nullable(); // nomor akun brankas

            $table->decimal('harian_limit_gram', 10, 3)->default(5.000); // jatah 5g/hari
            $table->decimal('komisi_persen', 5, 2)->default(0.00);      // komisi / gram

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_mitra_brankas');
    }
};
