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
        Schema::create('payments', function (Blueprint $table) {
            // ✅ Identifiant unique
            $table->uuid('id')->primary();

            // ✅ Clés étrangères
            $table->uuid('invoice_id')->index();
            $table->uuid('tenant_id')->index();
            $table->uuid('contact_id')->index();

            // ✅ Données de paiement
            $table->integer('amount_paid');
            $table->integer('remaining_amount');
            $table->date('payment_date');
            $table->string('payment_type');
            $table->enum('payment_source', ['client', 'supplier'])->default('client');

            $table->timestamps();

            // ✅ Contraintes de clé étrangère (avec suppression en cascade)
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');

            // ✅ Index combiné pour accélérer les recherches multi-critères
            $table->index(['tenant_id', 'invoice_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('payments');
    }
};
