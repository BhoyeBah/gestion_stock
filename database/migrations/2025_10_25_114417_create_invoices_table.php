<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number')->unique()->nullable()->index();
            $table->datetime('invoice_date')->index();
            $table->date('due_date')->nullable();
            $table->text('note')->nullable();
            $table->uuid('tenant_id')->nullable();
            $table->uuid('supplier_id')->nullable();
            $table->uuid('warehouse_id')->nullable();

            $table->enum('status', ['DRAFT', 'PARTIALLY_PAID', 'PAID', 'CANCELLED'])
                ->default('DRAFT')
                ->index();

            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('set null');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('set null');

            $table->unique(['invoice_number', 'tenant_id'], "tenant_invoice_unique");
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invoice_id')->index();
            $table->uuid('product_id')->index();
            $table->integer('quantity')->default(1);
            $table->bigInteger('purchase_price')->default(0);
            $table->bigInteger('total_line')->default(0);

            $table->timestamps();


            $table->foreign('invoice_id')
                ->references('id')->on('invoices')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
