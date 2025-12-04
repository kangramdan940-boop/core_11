<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
    Schema::create('sys_user', function (Blueprint $table) {
        $table->id();

        $table->string('name', 150)->nullable();
        $table->string('username', 100)->nullable();

        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable(); // FIXED

        $table->string('password');

        $table->string('role', 30)->default('customer');

        $table->unsignedBigInteger('master_customer_id')->nullable();
        $table->unsignedBigInteger('master_agen_id')->nullable();
        $table->unsignedBigInteger('master_mitra_brankas_id')->nullable();
        $table->unsignedBigInteger('master_admin_id')->nullable();

        $table->boolean('is_active')->default(true);
        $table->timestamp('last_login_at')->nullable();

        $table->rememberToken();   // OK
        $table->timestamps();

        $table->foreign('master_customer_id')->references('id')->on('master_customer')->nullOnDelete();
        $table->foreign('master_agen_id')->references('id')->on('master_agen')->nullOnDelete();
        $table->foreign('master_mitra_brankas_id')->references('id')->on('master_mitra_brankas')->nullOnDelete();
        $table->foreign('master_admin_id')->references('id')->on('master_admin')->nullOnDelete();
    });
}

 
    public function down(): void
    {
        Schema::dropIfExists('sys_user');
    }
};
