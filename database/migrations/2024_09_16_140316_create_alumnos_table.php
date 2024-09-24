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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->integer('id_version')->nullable(false);
            $table->string('rut')->nullable(false);
            $table->string('nombre')->nullable(false);
            $table->string('correo_electronico')->nullable(false);
            $table->decimal('nota', total: 3, places: 1)->nullable(true);
            $table->integer('asistencia')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
