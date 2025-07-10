<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('tenant_id')
                ->nullable()
                ->after('id') // juste après id, pour la lisibilité
                ->constrained('tenants')
                ->onDelete('cascade'); // si on supprime un tenant, ses rôles sautent
        });
    }

    public function down(): void {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
