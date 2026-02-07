<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sys_notification', function (Blueprint $table) {
            if (!Schema::hasColumn('sys_notification', 'ref_type')) {
                $table->string('ref_type', 30)->nullable()->after('channel');
            }
            if (!Schema::hasColumn('sys_notification', 'ref_id')) {
                $table->unsignedBigInteger('ref_id')->nullable()->after('ref_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sys_notification', function (Blueprint $table) {
            if (Schema::hasColumn('sys_notification', 'ref_id')) {
                $table->dropColumn('ref_id');
            }
            if (Schema::hasColumn('sys_notification', 'ref_type')) {
                $table->dropColumn('ref_type');
            }
        });
    }
};