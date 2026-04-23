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
    Schema::create('consultations', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Usamos UUID para seguridad en recetas
        $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
        $table->foreignUuid('patient_id')->constrained('patients')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // El Doctor
        $table->foreignUuid('clinic_id')->constrained('clinics')->onDelete('cascade');
        
        // Datos Clínicos
        $table->json('vitals')->nullable(); // Peso, talla, presión, temperatura
        $table->text('subjective')->nullable(); // Síntomas y motivo
        $table->text('objective')->nullable();  // Exploración física
        $table->text('assessment')->nullable(); // Diagnóstico
        $table->text('plan')->nullable();       // Tratamiento/Instrucciones
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
