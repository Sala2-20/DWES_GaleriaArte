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
        Schema::table('obras', function (Blueprint $table) {
            if (!Schema::hasColumn('obras', 'id_estilo')) {
                $table->unsignedBigInteger('id_estilo')->after('id');
            }
            $table->foreign('id_estilo') // Columna local
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
        Schema::table('obras', function (Blueprint $table) {
            $table->dropColumn('id_estilo');
        });
    }
};
