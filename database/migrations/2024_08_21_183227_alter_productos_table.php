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
                $table->integer('id_tipo_producto')->nullable(false);
                $table->foreign('id_tipo_producto')->references('id')->on('tipo_productos');
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
