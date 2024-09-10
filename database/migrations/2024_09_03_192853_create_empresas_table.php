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
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->string('rut')->nullable(false);
            $table->string('razon_social')->nullable(false);
            $table->string('giro')->nullable(false);
            $table->string('correo_electronico')->nullable(false);
            $table->string('direccion')->nullable(false);
            $table->string('telefono')->nullable(false);
            $table->string('sitio_web')->nullable(false);
            $table->string('logo')->nullable(false);
            $table->integer('id_banco')->nullable(false);
            $table->integer('id_tipo_cuenta')->nullable(false);
            $table->string('numero_cuenta')->nullable(false);
        });
    }
    /**
     * RUT, razón social, datos bancarios, correo electrónico, dirección, teléfono sitio web logo.
     */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
