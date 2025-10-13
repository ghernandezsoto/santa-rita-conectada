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
            // --- INICIO DE LA MODIFICACIÓN ---
            // Se añade la columna para rastrear el cambio de contraseña.
            // Es 'nullable' porque estará vacía para los usuarios nuevos.
            // Se coloca después de la columna 'remember_token' por orden.
            $table->timestamp('password_changed_at')->nullable()->after('remember_token');
            // --- FIN DE LA MODIFICACIÓN ---
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // --- INICIO DE LA MODIFICACIÓN ---
            // Si se revierte la migración, se elimina la columna.
            $table->dropColumn('password_changed_at');
            // --- FIN DE LA MODIFICACIÓN ---
        });
    }
};