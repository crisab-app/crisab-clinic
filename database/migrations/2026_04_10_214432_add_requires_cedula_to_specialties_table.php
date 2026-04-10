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
    Schema::table('specialties', function (Blueprint $table) {
        $table->boolean('requires_cedula')->default(false)->after('name');
    });
}

public function down()
{
    Schema::table('specialties', function (Blueprint $table) {
        $table->dropColumn('requires_cedula');
    });
}
};
