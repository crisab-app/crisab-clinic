<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('medications', function (Blueprint $table) {
        $table->id();
        $table->foreignUuid('clinic_id')->constrained()->cascadeOnDelete(); // Cada clínica tiene su catálogo
        $table->string('name'); // Ej. Aspirina Protect 100mg
        $table->string('generic_name')->nullable(); // Ej. Ácido Acetilsalicílico
        $table->string('presentation')->nullable(); // Ej. Caja con 30 tabletas
        $table->boolean('is_antibiotic')->default(false);
        $table->boolean('is_controlled')->default(false); // MAGIA PARA COFEPRIS
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
