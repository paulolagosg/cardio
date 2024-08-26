<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('trazabilidad_mantenciones')) {
            Schema::table('trazabilidad_mantenciones', function (Blueprint $table) {
                $table->integer('id_estado_mantencion')->nullable(true);
                $table->foreign('id_estado_mantencion')->references('id')->on('estados_mantenciones');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
