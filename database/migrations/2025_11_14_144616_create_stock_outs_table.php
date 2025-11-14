<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            
            $table->uuid('tenant_id')->index();


            $table->uuid('batch_id')->index();


            $table->integer('quantity');


            $table->string('reason')->nullable();

            $table->timestamps();


            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};
