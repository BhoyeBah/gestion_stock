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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100)->index();
            $table->text('address')->nullable();
            $table->uuid('manager_id')->nullable(); // responsable de l'entrepôt
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->uuid('tenant_id');

            // Foreign keys
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // Unicité du nom d’entrepôt par tenant
            $table->unique(['tenant_id', 'name'], 'tenant_warehouse_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
