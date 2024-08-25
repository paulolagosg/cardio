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
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->string('nombre', 2000)->nullable(false);
            $table->integer('id_rubro')->nullable(false);
            $table->foreign('id_rubro')->references('id')->on('rubros');
            $table->string('razon_social', 2000)->nullable(false);
            $table->string('rut', 2000)->nullable(false);
            $table->string('giro', 2000)->nullable(false);
            $table->string('direccion', 2000)->nullable(false);
            $table->integer('id_region')->nullable(false);
            $table->foreign('id_region')->references('id')->on('regiones');
            $table->integer('id_comuna')->nullable(false);
            $table->foreign('id_comuna')->references('id')->on('comunas');
            $table->bigInteger('telefono')->nullable(true);
            $table->string('sitio_web', 2000)->nullable(true);
            $table->string('correo', 2000)->nullable(true);
            $table->string('id_licitacion', 2000)->nullable(true);
            $table->string('slug')->nullable(false);
            $table->integer('estado')->nullable(false)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
