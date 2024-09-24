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
        Schema::create('versiones', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->string('nombre')->nullable(false);
            $table->integer('id_cliente')->nullable(false);
            $table->integer('id_curso')->nullable(false);
            $table->integer('id_modalidad')->nullable(false);
            $table->date('fecha_version')->nullable(false);
            $table->integer('id_usuario_instructor')->nullable(false);
            $table->integer('id_usuario_firmante')->nullable(false);
            $table->string('firma')->nullable(false);
            $table->string('ruta')->nullable(false);
            $table->integer('horas')->nullable(false);
            $table->string('contraparte')->nullable(false);
            $table->string('rut')->nullable(false);
            $table->string('correo_electronico')->nullable(false);
            $table->string('telefono')->nullable(false);
            $table->date('fecha_certificado')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->integer('estado')->nullable(false)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versiones');
    }
};
