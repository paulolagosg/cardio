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
        Schema::create('trazabilidades_productos_tmp', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->integer('id_trazabilidad')->nullable(false);
            $table->foreign('id_trazabilidad')->references('id')->on('trazabilidades');
            $table->integer('id_producto')->nullable(false);
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->string('lote')->nullable(false);
            $table->date('vencimiento')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trazabilidades_productos_tmp');
    }
};
