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
        if (Schema::hasTable('cotizaciones')) {
            Schema::table('cotizaciones', function (Blueprint $table) {
                $table->dateTime('fecha')->change();
                $table->bigInteger('costo_envio')->default(0);
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
