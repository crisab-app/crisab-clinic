<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatchUuidColumnToActivityLogTable extends Migration
{
 public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Verificamos si la columna 'batch_uuid' NO existe antes de crearla
            if (!Schema::hasColumn('activity_log', 'batch_uuid')) {
                // Mantén la línea original que tenías, solo ponla aquí adentro. 
                // Seguramente es algo como esto:
                $table->uuid('batch_uuid')->nullable()->after('properties'); 
            }
        });
    }
    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->dropColumn('batch_uuid');
        });
    }
}
