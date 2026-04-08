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
    Schema::create('clinic_resources', function (Blueprint $table) {
        $table->id();
        // Relación con la clínica usando UUID
        $table->foreignUuid('clinic_id')->constrained('clinics')->cascadeOnDelete();
        
        $table->string('name'); // Ej: "Consultorio 1", "Incubadora A", "Quirófano 2"
        $table->string('type'); // Ej: "consultorio", "cama", "equipo", "quirofano"
        $table->boolean('is_active')->default(true); // Para pausar un consultorio si está en mantenimiento
        $table->text('description')->nullable(); // Ej: "Cama con monitor de signos vitales"
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_resources');
    }
};
