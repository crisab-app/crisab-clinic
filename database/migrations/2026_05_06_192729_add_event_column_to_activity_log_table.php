<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventColumnToActivityLogTable extends Migration
{
public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Verificamos si la columna 'event' NO existe antes de crearla
            if (!Schema::hasColumn('activity_log', 'event')) {
                $table->string('event')->nullable()->after('subject_type');
            }
        });
    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->dropColumn('event');
        });
    }
}
