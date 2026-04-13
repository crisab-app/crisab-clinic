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
    Schema::create('patient_tokens', function (Blueprint $table) {
        $table->id();
        $table->foreignUuid('patient_id')->constrained('patients')->cascadeOnDelete();
        $table->string('token')->unique(); // El código mágico: xyz-123
        $table->timestamp('expires_at'); // Cuándo caduca el link
        $table->boolean('is_used')->default(false); // Para saber si ya lo llenó
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_tokens');
    }
};
