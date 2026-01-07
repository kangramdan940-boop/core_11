<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('rekening_nomor', 50);
            $table->string('bank_nama', 100);
            $table->string('rekening_atas_nama', 150);
            $table->unsignedInteger('expired_minutes')->default(1440);
            $table->text('konfirmasi_petunjuk')->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->text('jasa_titip_informasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_payment_settings');
    }
};