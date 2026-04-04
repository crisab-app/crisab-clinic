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
    Schema::create('clinics', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('name');
        $table->string('visual_id')->unique();
        $table->string('country'); // <-- Agregamos país
        $table->string('timezone')->default('America/Cancun'); // <-- Agregamos zona horaria
        $table->string('billing_plan')->default('trial'); // Cambié 'pro' a 'trial' para nuevos registros
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
