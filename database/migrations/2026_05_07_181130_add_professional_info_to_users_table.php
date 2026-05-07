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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'professional_license')) {
                $table->string('professional_license')->nullable()->after('member_type');
            }
            
            if (!Schema::hasColumn('users', 'university')) {
                $table->string('university')->nullable()->after('professional_license');
            }
            
            if (!Schema::hasColumn('users', 'specialty_license')) {
                $table->string('specialty_license')->nullable()->after('university');
            }
            
            if (!Schema::hasColumn('users', 'signature_type')) {
                $table->enum('signature_type', ['manual', 'fiel'])->default('manual')->after('specialty_license');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
