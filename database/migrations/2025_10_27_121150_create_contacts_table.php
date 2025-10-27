<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname');
            $table->string('phone_number', 15)->index();
            $table->string('address')->nullable();
            $table->uuid('tenant_id')->index();
            $table->enum('type', ['client', 'supplier'])->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();


            // FK : s'assurer que tenants.id est bien un UUID
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            // Eviter les doublons de numéro pour le même tenant
            $table->unique(['tenant_id', 'phone_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
