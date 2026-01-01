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
        Schema::create('mobile_app_configs', function (Blueprint $table) {
            $table->id();
            $table->string('login_page_icon')->nullable();
            $table->string('information_link')->nullable();
            $table->boolean('development_mode')->nullable();
            $table->boolean('status_naik')->nullable();
            $table->boolean('status_turun')->nullable();
            $table->string('welcome_title')->nullable();
            $table->text('welcome_description')->nullable();
            $table->boolean('broadcast_info_banner_status')->nullable();
            $table->text('broadcast_info_banner_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_app_configs');
    }
};