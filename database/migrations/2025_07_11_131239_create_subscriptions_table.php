<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Remplacer l'id classique par UUID

            $table->uuid('tenant_id');
            $table->uuid('plan_id');

            $table->decimal('amount_paid', 12, 0)->default(0); // en FCFA, entier
            $table->string('payment_method')->nullable(); // ex: "wave", "orange_money", "carte", etc.

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->boolean('is_active')->default(false); // doit être validé manuellement ou automatiquement

            $table->timestamps();

            // Contraintes de clé étrangère UUID
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('restrict');
        });
    }

    public function down(): void {
        Schema::dropIfExists('subscriptions');
    }
};
