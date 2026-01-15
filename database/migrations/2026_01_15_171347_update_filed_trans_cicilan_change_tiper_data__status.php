<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Tambahkan union set sementara agar bisa update nilai lama -> baru
        DB::statement("
            ALTER TABLE trans_cicilan
            MODIFY COLUMN status ENUM(
                'menunggu DP','active','pembayaran telat','sudah di bayar','selesai','canceled',
                'completed','cancelled','defaulted'
            ) NOT NULL DEFAULT 'active'
        ");

        // 2) Migrasi nilai lama ke padanan baru
        DB::statement("UPDATE trans_cicilan SET status = 'selesai' WHERE status = 'completed'");
        DB::statement("UPDATE trans_cicilan SET status = 'canceled' WHERE status = 'cancelled'");
        DB::statement("UPDATE trans_cicilan SET status = 'pembayaran telat' WHERE status = 'defaulted'");

        // 3) Kunci final ke set baru sesuai permintaan
        DB::statement("
            ALTER TABLE trans_cicilan
            MODIFY COLUMN status ENUM(
                'menunggu DP','active','pembayaran telat','sudah di bayar','selesai','canceled'
            ) NOT NULL DEFAULT 'active'
        ");
    }

    public function down(): void
    {
        // 1) Tambahkan union set sementara agar bisa rollback nilai baru -> lama
        DB::statement("
            ALTER TABLE trans_cicilan
            MODIFY COLUMN status ENUM(
                'menunggu DP','active','pembayaran telat','sudah di bayar','selesai','canceled',
                'completed','cancelled','defaulted'
            ) NOT NULL DEFAULT 'active'
        ");

        // 2) Rollback nilai baru ke set lama
        DB::statement("UPDATE trans_cicilan SET status = 'completed' WHERE status = 'selesai'");
        DB::statement("UPDATE trans_cicilan SET status = 'cancelled' WHERE status = 'canceled'");
        DB::statement("UPDATE trans_cicilan SET status = 'defaulted' WHERE status = 'pembayaran telat'");

        // 3) Kembalikan ke set lama
        DB::statement("
            ALTER TABLE trans_cicilan
            MODIFY COLUMN status ENUM('active','completed','cancelled','defaulted')
            NOT NULL DEFAULT 'active'
        ");
    }
};