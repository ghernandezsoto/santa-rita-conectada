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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->enum('tipo', ['Ingreso', 'Egreso']);
            $table->decimal('monto', 10, 2); // 10 dígitos en total, 2 decimales
            $table->string('descripcion');
            $table->foreignId('user_id')->constrained(); // Usuario que registró la transacción
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccions');
    }
};
