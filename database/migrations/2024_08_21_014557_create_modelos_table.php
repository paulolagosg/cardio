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
        Schema::create('modelos', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->string('nombre', 200)->nullable(false);
            $table->integer('id_marca')->nullable(false);
            $table->foreign('id_marca')->references('id')->on('marcas');
            $table->string('slug')->nullable(false);
            $table->integer('estado')->nullable(false)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};
