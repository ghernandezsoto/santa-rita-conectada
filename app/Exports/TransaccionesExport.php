<?php

namespace App\Exports;

use App\Models\Transaccion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaccionesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ahora cargamos también la relación 'socio' para que esté disponible
        return Transaccion::with('user', 'socio')->orderBy('fecha', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Define los nuevos títulos de las columnas en el Excel
        return [
            'ID',
            'Fecha',
            'Tipo',
            'Descripción',
            'Monto',
            'Socio Aportante', // <-- NUEVA COLUMNA
            'Registrado por',
        ];
    }

    /**
     * @param Transaccion $transaccion
     * @return array
     */
    public function map($transaccion): array
    {
        // Define qué dato va en cada columna, incluyendo el nombre del socio
        return [
            $transaccion->id,
            $transaccion->fecha,
            $transaccion->tipo,
            $transaccion->descripcion,
            $transaccion->monto,
            $transaccion->socio ? $transaccion->socio->nombre : 'N/A', // <-- NUEVO DATO
            $transaccion->user->name,
        ];
    }
}