<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sys_notification', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sys_user_id')->nullable();

            $table->string('channel', 20)->default('system'); 
            // system, email, wa, sms

            $table->string('title', 150)->nullable();
            $table->text('message')->nullable();
            $table->text('data_json')->nullable(); // payload tambahan (JSON string)

            $table->enum('status', ['pending', 'sent', 'failed'])
                  ->default('pending');

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->text('error_message')->nullable();

            $table->boolean('is_read')->default(false);

            $table->timestamps();

            $table->foreign('sys_user_id')
                ->references('id')->on('sys_user')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_notification');
    }
};
