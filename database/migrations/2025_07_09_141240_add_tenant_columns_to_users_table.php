<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_owner')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('phone')->nullable();
            $table->string('profile_photo')->nullable();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['tenant_id', 'is_owner', 'is_active', 'phone', 'profile_photo']);
        });
    }
};
