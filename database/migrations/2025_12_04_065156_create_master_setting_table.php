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
        Schema::create('master_setting', function (Blueprint $table) {
            $table->id();

            $table->string('key', 100)->unique();   // misal: cicilan_min_month, fee_admin_po, wa_notif_number
            $table->text('value')->nullable();      // isi nilai (string/json)
            $table->string('label', 150)->nullable(); // nama yang enak dibaca di UI
            $table->string('group', 50)->nullable();  // misal: 'cicilan', 'po', 'notifikasi'

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_setting');
    }
};
