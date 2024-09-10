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
        if (Schema::hasTable('productos')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('sku')->nullable(true);
                $table->text('descripcion')->nullable(true);
                $table->string('nombre_archivo')->nullable(true);
                $table->string('ruta')->nullable(true);
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
