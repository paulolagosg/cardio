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
        Schema::create('cotizaciones_productos', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->integer('id_cotizacion')->nullable(false);
            $table->integer('id_producto')->nullable(false);
            $table->bigInteger('precio')->nullable(false);
            $table->integer('cantidad')->nullable(false);
            $table->bigInteger('descuento')->nullable(false);
            $table->bigInteger('descuento_pesos')->nullable(false);
            $table->bigInteger('unitario')->nullable(false);
            $table->bigInteger('subtotal')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_productos');
    }
};
