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
        Schema::create('trazabilidades', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->integer('id_cliente')->nullable(false);
            $table->foreign('id_cliente')->references('id')->on('clientes');
            $table->integer('id_producto')->nullable(false);
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->string('numero_serie')->nullable(false);
            $table->string('ubicacion', 4000)->nullable(false);
            $table->string('slug')->nullable(false);
            $table->integer('estado')->nullable(false)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trazabilidad');
    }
};
