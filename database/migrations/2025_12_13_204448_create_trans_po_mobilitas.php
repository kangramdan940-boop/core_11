<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_po_mobilitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_po_id');
            $table->date('tanggal');
            $table->string('kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->foreign('trans_po_id')
                ->references('id')->on('trans_po')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_po_mobilitas');
    }
};