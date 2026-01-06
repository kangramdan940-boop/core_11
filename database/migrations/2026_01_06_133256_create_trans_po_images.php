<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_po_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_po_id');
            $table->string('file_path', 255);
            $table->string('mime_type', 100);
            $table->string('title', 100)->nullable();
            $table->unsignedBigInteger('size_bytes');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('trans_po_id')->references('id')->on('trans_po')->cascadeOnDelete();
            $table->unique('trans_po_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_po_images');
    }
};