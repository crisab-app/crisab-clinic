<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('clinic_id')->constrained('clinics')->cascadeOnDelete();
            
            // Datos Personales
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable(); // Masculino, Femenino, Otro
            
            // Datos Clínicos Básicos
            $table->string('blood_type')->nullable(); // O+, A-, etc.
            $table->text('allergies')->nullable();
            $table->text('notes')->nullable(); // Para antecedentes rápidos
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};