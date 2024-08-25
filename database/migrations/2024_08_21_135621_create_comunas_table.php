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
        Schema::create('comunas', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->string('nombre', 200)->nullable(false);
            $table->integer('id_region')->nullable(false);
            $table->foreign('id_region')->references('id')->on('regiones');
            $table->string('slug')->nullable(false);
            $table->integer('estado')->nullable(false)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunas');
    }
};
