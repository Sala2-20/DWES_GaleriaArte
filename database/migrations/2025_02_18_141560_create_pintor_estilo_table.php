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
        Schema::create('pintor_estilo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pintor_id');
            $table->unsignedBigInteger('estilo_id');

            $table->foreign('pintor_id') // Columna local
                  ->references('id') // Columna referenciada
                  ->on('pintors') // Tabla referenciada
                  ->onDelete('cascade') // Acción al eliminar (cascade, set null, restrict)
                  ->onUpdate('cascade');

            $table->foreign('estilo_id') // Columna local
                  ->references('id') // Columna referenciada
                  ->on('estilos') // Tabla referenciada
                  ->onDelete('cascade') // Acción al eliminar (cascade, set null, restrict)
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pintor_estilo');
    }
};
