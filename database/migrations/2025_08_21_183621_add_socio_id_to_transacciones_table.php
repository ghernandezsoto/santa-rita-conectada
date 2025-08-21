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
        Schema::table('transacciones', function (Blueprint $table) {
            // Añade la clave foránea a la tabla socios.
            // Es 'nullable' porque no todas las transacciones (ej. gastos generales) pertenecen a un socio.
            // onDelete('set null') significa que si un socio es eliminado, el registro de la transacción no se borra, solo se quita el vínculo.
            $table->foreignId('socio_id')->nullable()->after('user_id')->constrained('socios')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['socio_id']);
            $table->dropColumn('socio_id');
        });
    }
};
