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
        $table->string('emergency_contact_name')->nullable()->after('phone');
        $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
    });
}

public function down()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->dropColumn(['emergency_contact_name', 'emergency_contact_phone']);
    });
}
};
