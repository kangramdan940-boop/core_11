<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_mitra_brankas', function (Blueprint $table) {
            $table->boolean('is_edit')->default(true)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('master_mitra_brankas', function (Blueprint $table) {
            $table->dropColumn('is_edit');
        });
    }
};