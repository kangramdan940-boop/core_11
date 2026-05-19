<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_push_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sys_user_id');
            $table->string('expo_push_token')->comment('Expo push token, format: ExponentPushToken[xxx]');
            $table->string('device_name')->nullable()->comment('Nama device untuk identifikasi');
            $table->string('platform')->nullable()->comment('ios / android');
            $table->boolean('is_active')->default(true)->comment('Token masih aktif atau tidak');
            $table->timestamp('last_used_at')->nullable()->comment('Terakhir digunakan untuk kirim notifikasi');
            $table->timestamps();

            $table->foreign('sys_user_id')
                ->references('id')
                ->on('sys_user')
                ->onDelete('cascade');

            // Satu user tidak boleh punya token yang sama dua kali
            $table->unique(['sys_user_id', 'expo_push_token'], 'user_token_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_push_tokens');
    }
};
