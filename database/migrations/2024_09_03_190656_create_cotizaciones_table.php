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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->integer('id_usuario')->nullable(false);
            $table->integer('id_empresa')->nullable(false);
            $table->string('solicitante', 200)->nullable(false);
            $table->string('correo_electronico')->nullable(false);
            $table->string('razon_social')->nullable(false);
            $table->string('rut')->nullable(false);
            $table->integer('telefono')->nullable(false);
            $table->string('direccion')->nullable(false);
            $table->string('giro')->nullable(false);
            $table->integer('id_region')->nullable(false);
            $table->integer('id_comuna')->nullable(false);
            $table->integer('id_vencimiento')->nullable(false);
            $table->integer('id_tipo_transporte')->nullable(false);
            $table->integer('id_tipo_pago')->nullable(false);
            $table->integer('id_plazo_pago')->nullable(false);
            $table->integer('id_tiempo_entrega')->nullable(false);
            $table->text('observaciones')->nullable(true);
            $table->date('fecha')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
