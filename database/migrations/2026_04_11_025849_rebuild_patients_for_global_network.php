<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Borramos la tabla aislada que hicimos hace rato
        Schema::dropIfExists('patients');

        // 2. Creamos la Tabla Global de Pacientes (Sin clinic_id)
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID Global y único
            $table->string('name');
            $table->string('email')->unique()->nullable(); // ÚNICO A NIVEL GLOBAL
            $table->string('curp', 18)->unique()->nullable(); // ÚNICO A NIVEL GLOBAL
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            
            // Datos Médicos Universales
            $table->string('blood_type')->nullable();
            $table->text('allergies')->nullable();
            
            $table->timestamps();
        });

        // 3. Creamos la Tabla Intermedia (El "Puente" entre Clínica y Paciente)
        Schema::create('clinic_patient', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignUuid('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->timestamps();

            // Evitamos que una clínica agregue al mismo paciente dos veces
            $table->unique(['clinic_id', 'patient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('clinic_patient');
        Schema::dropIfExists('patients');
    }
};