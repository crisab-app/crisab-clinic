<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resource_types', function (Blueprint $table) {
            $table->id();
            // Vinculamos el tipo de recurso con la clínica específica (Soporte para UUID)
            $table->foreignUuid('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->string('name'); // Ej: "Cabina de Masaje", "Quirófano"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resource_types');
    }
};