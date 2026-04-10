<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_type')->default('staff')->after('email'); // Ej: medico, enfermero, recepcionista
            $table->string('professional_id')->nullable()->after('member_type'); // Cédula Profesional
            $table->string('specialty')->nullable()->after('professional_id'); // Especialidad
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['member_type', 'professional_id', 'specialty']);
        });
    }
};