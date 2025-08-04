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
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('price'); // en FCFA
            $table->integer('duration_days'); // ex: 30 jours
            $table->integer('max_users')->nullable(); // limite d'utilisateurs (optionnel)
            $table->integer('max_storage_mb')->nullable(); // stockage max autorisé
            $table->boolean('is_active')->default(true); // peut être utilisé ?
            $table->text('description')->nullable(); // résumé du plan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
