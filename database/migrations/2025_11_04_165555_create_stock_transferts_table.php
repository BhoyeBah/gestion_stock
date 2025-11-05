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
        Schema::create('stock_transferts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('product_id')->index();
            $table->uuid('source_warehouse_id')->index();
            $table->uuid('target_warehouse_id')->index();
            $table->uuid('source_batch_id')->index();
            $table->uuid('target_batch_id')->index();
            $table->integer('quantity');

            $table->timestamps();

            // 🔒 Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('source_warehouse_id')->references('id')->on('warehouses')->onDelete('restrict');
            $table->foreign('target_warehouse_id')->references('id')->on('warehouses')->onDelete('restrict');
            $table->foreign('source_batch_id')->references('id')->on('batches')->onDelete('restrict');
            $table->foreign('target_batch_id')->references('id')->on('batches')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transferts');
    }
};
