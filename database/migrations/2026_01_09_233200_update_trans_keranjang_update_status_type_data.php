<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE trans_keranjang MODIFY COLUMN status_order VARCHAR(32) NOT NULL DEFAULT 'perlu_dibayar'");
        DB::statement("UPDATE trans_keranjang SET status_order = 'diproses' WHERE status_order = 'dikemas'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE trans_keranjang MODIFY COLUMN status_order ENUM('perlu_dibayar','dikemas','dikirim','dibatalkan','selesai') NOT NULL DEFAULT 'perlu_dibayar'");
        DB::statement("UPDATE trans_keranjang SET status_order = 'dikemas' WHERE status_order = 'diproses'");
    }
};