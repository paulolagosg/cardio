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
        if (Schema::hasTable('trazabilidad_productos')) {
            Schema::table('trazabilidad_productos', function (Blueprint $table) {
                $table->integer('id_estado_vencimiento')->nullable(true);
                $table->foreign('id_estado_vencimiento')->references('id')->on('estados_vencimientos');
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
