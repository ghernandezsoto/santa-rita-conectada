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
        Schema::create('subsidios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_subsidio');
            $table->text('descripcion');
            $table->decimal('monto_solicitado', 10, 2);
            $table->enum('estado', ['Postulando', 'Aprobado', 'Rechazado', 'Finalizado'])->default('Postulando');
            $table->foreignId('socio_id')->constrained('socios'); // El socio que postula
            $table->foreignId('user_id')->constrained('users'); // El miembro de la directiva que gestiona
            $table->text('observaciones_directiva')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsidios');
    }
};
