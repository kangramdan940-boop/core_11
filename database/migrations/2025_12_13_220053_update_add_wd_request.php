<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mitra_withdrawals', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('completed_at');
            $table->string('payment_proof_url', 255)->nullable()->after('admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('mitra_withdrawals', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'payment_proof_url']);
        });
    }
};