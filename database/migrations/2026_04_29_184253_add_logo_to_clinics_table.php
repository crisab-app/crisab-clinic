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
        Schema::table('clinics', function (Blueprint $table) {
            // Verificamos si la columna NO existe antes de intentar agregarla
            if (!Schema::hasColumn('clinics', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('name');
            }
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            // Verificamos si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('clinics', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
        });
    }
};
