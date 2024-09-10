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
                $table->integer('guia_despacho')->nullable(true);
                $table->integer('factura')->nullable(true);
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
