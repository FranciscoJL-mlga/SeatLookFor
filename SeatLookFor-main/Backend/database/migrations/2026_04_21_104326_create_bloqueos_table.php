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
        Schema::create('bloqueos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idAsi');
            $table->unsignedBigInteger('idUsu');
            $table->unsignedBigInteger('idEve');
            $table->timestamp('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueos');
    }
};
