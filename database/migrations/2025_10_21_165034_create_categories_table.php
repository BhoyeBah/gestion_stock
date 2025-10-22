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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // tenant_id : ajuster le type si votre table tenants utilise uuid
            $table->uuid('tenant_id')->index();

            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            // clé étrangère vers tenants (ajuste si tenants.id n'est pas unsignedBigInteger)
            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');

            // contraintes d'unicité par tenant (composite unique)
            $table->unique(['tenant_id', 'name'], 'categories_tenant_name_unique');
            $table->unique(['tenant_id', 'slug'], 'categories_tenant_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On retire proprement les contraintes avant de supprimer la table
        Schema::table('categories', function (Blueprint $table) {
            // Supprime les clés uniques si elles existent
            $table->dropUnique('categories_tenant_name_unique');
            $table->dropUnique('categories_tenant_slug_unique');

            // Supprime la contrainte de clé étrangère
            $table->dropForeign(['tenant_id']);

            // Optionnel : supprime l'index si tu veux nettoyer explicitement
            $table->dropIndex(['tenant_id']);
        });

        Schema::dropIfExists('categories');
    }
};
