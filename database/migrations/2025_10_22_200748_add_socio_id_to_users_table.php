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
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna 'socio_id' que puede ser nula.
            // Se vincula a la tabla 'socios'.
            // Si un socio es eliminado, el 'socio_id' en users se pondrá a null.
            $table->foreignId('socio_id')
                ->nullable()
                ->after('id') // para ponerla cerca del 'id' de usuario
                ->constrained('socios')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Elimina la clave foránea y luego la columna
            $table->dropForeign(['socio_id']);
            $table->dropColumn('socio_id');
        });
    }
};
