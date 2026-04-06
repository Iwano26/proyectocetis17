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
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('correo')->index(); // Correo de la tabla 'persona'
            $table->string('token');           // El UUID/Token aleatorio
            $table->timestamp('expires_at');   // Fecha de expiración (los 15 min que pusimos)
            $table->boolean('used')->default(0); // Para saber si ya se usó (seguridad extra)
            $table->timestamps();              // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};