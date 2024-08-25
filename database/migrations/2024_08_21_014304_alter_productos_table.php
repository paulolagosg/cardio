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
        if (Schema::hasTable('productos')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->integer('id_marca')->nullable(false);
                $table->foreign('id_marca')->references('id')->on('marcas');
                $table->integer('id_modelo')->nullable(false);
                $table->foreign('id_modelo')->references('id')->on('modelos');
                $table->string('lote', 1000)->nullable(true);
                $table->string('numero_serie', 1000)->nullable(true);
                $table->date('vencimiento')->nullable(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
