<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gold_stock_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_gold_stock_id')->nullable();

            $table->string('issuer_company')->nullable();
            $table->string('issuer_business_unit')->nullable();
            $table->text('issuer_address')->nullable();
            $table->string('issuer_website')->nullable();
            $table->string('issuer_phone')->nullable();
            $table->string('issuer_npwp')->nullable();
            $table->string('issuer_npwp_holder')->nullable();
            $table->text('issuer_npwp_address')->nullable();

            $table->string('authorized_receiver_name')->nullable();
            $table->string('authorized_receiver_nik', 20)->nullable();

            $table->string('invoice_number')->nullable();
            $table->string('reference')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('date_raw')->nullable();
            $table->date('date')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('membership_number')->nullable();
            $table->string('membership_tier')->nullable();

            $table->string('service_name')->nullable();
            $table->string('boutique_code_name')->nullable();
            $table->text('boutique_location')->nullable();

            $table->unsignedBigInteger('grand_total_idr')->default(0);
            $table->unsignedBigInteger('dpp_idr')->default(0);
            $table->unsignedTinyInteger('ppn_rate')->nullable();
            $table->unsignedBigInteger('ppn_idr')->default(0);
            $table->char('currency', 3)->default('IDR');

            $table->string('payment_method')->nullable();
            $table->string('virtual_account')->nullable();
            $table->string('payment_no')->nullable();
            $table->string('created_by')->nullable();
            $table->string('print_by')->nullable();

            $table->longText('raw_text')->nullable();
            $table->json('notes')->nullable();

            $table->timestamps();

            $table->index('master_gold_stock_id');
            $table->index('invoice_number');
            $table->index('payment_no');
            $table->foreign('master_gold_stock_id')
                ->references('id')
                ->on('master_gold_stock')
                ->onDelete('cascade');
        });

        Schema::create('gold_stock_document_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedSmallInteger('no');
            $table->string('description', 255);
            $table->unsignedInteger('quantity_pcs');
            $table->decimal('weight_kg', 12, 6);
            $table->unsignedBigInteger('unit_price_idr');
            $table->unsignedBigInteger('total_idr');
            $table->timestamps();

            $table->index('document_id');
            $table->foreign('document_id')
                ->references('id')
                ->on('gold_stock_documents')
                ->onDelete('cascade');
            $table->unique(['document_id', 'no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gold_stock_document_items');
        Schema::dropIfExists('gold_stock_documents');
    }
};