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
    Schema::table('medications', function (Blueprint $table) {
        $table->integer('current_stock')->default(0)->after('presentation');
        $table->integer('min_stock')->default(5)->after('current_stock'); // Alerta de stock bajo
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            //
        });
    }
};
