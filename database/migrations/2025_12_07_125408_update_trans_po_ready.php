<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['trans_po_log', 'trans_ready_log'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('sys_user')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('sys_user')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['trans_po_log', 'trans_ready_log'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('created_by');
                $table->dropConstrainedForeignId('updated_by');
            });
        }
    }
};