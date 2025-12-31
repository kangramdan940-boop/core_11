<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->timestamp('notify_transfer_sent_at')->nullable()->after('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::table('trans_po', function (Blueprint $table) {
            $table->dropColumn('notify_transfer_sent_at');
        });
    }
};