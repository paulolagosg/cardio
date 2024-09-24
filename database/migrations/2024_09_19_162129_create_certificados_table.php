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
        Schema::create('certificados', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->integer('id_alumno')->nullable(false);
            $table->string('ruta')->nullable(false);
            $table->string('codigo')->nullable(false);
            $table->dateTime('fecha')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
