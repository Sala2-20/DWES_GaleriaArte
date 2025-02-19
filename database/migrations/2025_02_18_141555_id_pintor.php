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
            if (!Schema::hasColumn('obras','id_pintor')) {
                $table->unsignedBigInteger('id_pintor')->after('id');
            }

            $table->foreign('id_pintor') // Columna local
                  ->references('id') // Columna referenciada
                  ->on('pintors') // Tabla referenciada
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
            $table->dropColumn('id_pintor');
        });
    }
};
