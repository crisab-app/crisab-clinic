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
    Schema::table('patients', function (Blueprint $table) {
        // El RFC ahora es global y único para detectar duplicados
        $table->string('rfc', 13)->unique()->nullable()->after('curp');
        $table->string('tax_name')->nullable()->after('rfc'); // Razón Social
        $table->string('tax_zip_code', 5)->nullable(); // CP para CFDI 4.0
        $table->string('tax_regime')->nullable(); // Régimen Fiscal
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
};
