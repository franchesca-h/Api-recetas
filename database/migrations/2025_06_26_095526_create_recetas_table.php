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
        Schema::create('nombre_de_tu_tabla', function (Blueprint $table) {
            // Tus definiciones de columnas aquí
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->integer('tiempo_preparacion');
            $table->timestamps();
        }); // Este es el único cierre para Schema::create
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nombre_de_tu_tabla');
    }
};
