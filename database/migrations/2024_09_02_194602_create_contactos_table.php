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
        Schema::create('contactos', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->bigInteger('id_cliente')->nullable(false);
            $table->bigInteger('id_tipo_contacto')->nullable(false);
            $table->string('nombre', 200)->nullable(false);
            $table->string('correo_electronico', 200)->nullable(false);
            $table->integer('telefono')->nullable(false);
            $table->integer('estado')->nullable(false)->default(1);
            $table->string('slug')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
