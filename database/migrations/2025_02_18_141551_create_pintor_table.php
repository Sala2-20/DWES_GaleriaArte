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
            Schema::create('pintors', function (Blueprint $table) {
                $table->id();
                $table->longText('descripcion');
                $table->binary('imagen');
                $table->string('tipo');
                $table->string('nacionalidad');
                $table->date('nacimiento');
                $table->string(column: 'nombre');
                $table->date('fallecimiento')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pintors');
    }
};
