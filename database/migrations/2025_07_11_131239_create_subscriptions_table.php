<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('restrict');

            $table->decimal('amount_paid', 12, 0)->default(0); // en FCFA, entier
            $table->string('payment_method')->nullable(); // ex: "wave", "orange_money", "carte", etc.

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();


            $table->boolean('is_active')->default(false); // doit être validé manuellement ou automatiquement

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('subscriptions');
    }
};
