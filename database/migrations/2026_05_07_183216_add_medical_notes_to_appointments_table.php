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
    Schema::table('appointments', function (Blueprint $table) {
        $table->text('diagnosis')->nullable()->after('reason'); // Diagnóstico médico
        $table->text('medical_notes')->nullable()->after('diagnosis'); // Notas privadas o evolución
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
