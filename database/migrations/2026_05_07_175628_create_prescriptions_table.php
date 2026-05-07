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
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('appointment_id')->constrained()->cascadeOnDelete(); // Conectado a la cita
        $table->foreignId('medication_id')->constrained()->cascadeOnDelete(); // El medicamento
        $table->string('dosage'); // Ej. "Tomar 1 tableta cada 8 horas por 5 días"
        $table->integer('quantity_prescribed')->default(1); // Para saber cuántas cajas se recetaron
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
