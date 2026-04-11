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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignUuid('clinic_id')->constrained('clinics')->cascadeOnDelete();
        $table->foreignUuid('patient_id')->constrained('patients')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->comment('El Médico');
        $table->foreignId('resource_id')->constrained('clinic_resources')->comment('El Consultorio/Mesa');
        
        $table->datetime('start_time');
        $table->datetime('end_time');
        $table->string('status')->default('scheduled'); // scheduled, confirmed, cancelled, completed
        $table->text('reason')->nullable(); // Motivo de la consulta
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
