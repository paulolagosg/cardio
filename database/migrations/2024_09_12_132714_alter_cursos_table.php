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
        // if (Schema::hasTable('cursos')) {
        //     Schema::table('cursos', function (Blueprint $table) {
        //         $table->bigInteger('id_modalidad')->nullable(true);
        //     });
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
