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
        Schema::create('socios', function (Blueprint $table) {
            $table->id(); // Corresponde al "Número del socio"
            $table->string('rut')->unique();
            $table->string('nombre');
            $table->string('domicilio');
            $table->string('profesion')->nullable();
            $table->integer('edad')->nullable();
            $table->string('estado_civil')->nullable();
            $table->date('fecha_ingreso');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('estado')->default('Activo'); // Ej: Activo, Inactivo
            $table->text('observaciones')->nullable();
            $table->timestamps(); // Columnas created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
