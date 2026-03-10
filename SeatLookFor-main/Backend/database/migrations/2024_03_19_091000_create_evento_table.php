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
        Schema::create('evento', function (Blueprint $table) {
            $table->id('idEve');
            $table->string('titulo');
            $table->string('estado');
            $table->string('valoracion');
            $table->string('ubicacion');
            $table->string('tipo');
            $table->string('descripcion');
            $table->string('portada');
            $table->time('duracion');
            $table->date('fecha');
            $table->string('categoria');
            $table->unsignedBigInteger('idEst');
            
            $table->foreign('idEst')->references('idEst')->on('establecimiento')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento');
    }
};
