<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_asset', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('category', 50)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('file_hash', 128)->unique();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_extension', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('sys_user')->nullOnDelete();
            $table->index(['type', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_asset');
    }
};