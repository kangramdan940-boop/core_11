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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->string('subject');
            $table->string('status')->default('pending'); // success, failed, pending
            $table->text('error_message')->nullable();
            $table->string('mail_type')->nullable(); // e.g. 'po_paid', 'po_shipping'
            $table->nullableMorphs('related'); // related_type, related_id
            $table->unsignedBigInteger('user_id')->nullable(); // Triggered by admin/system
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};