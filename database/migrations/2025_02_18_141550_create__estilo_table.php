<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('estilos')) {
            Schema::create('estilos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('obras_principales');
                $table->integer('epoca_inicio')->unsigned();
                $table->integer('epoca_final')->unsigned();
                $table->longText( 'caracteristicas');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estilos');
    }
};

