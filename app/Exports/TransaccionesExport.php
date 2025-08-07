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
        // Obtenemos todas las transacciones con el usuario que la registró
        return Transaccion::with('user')->orderBy('fecha', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Define los títulos de las columnas en el Excel
        return [
            'ID',
            'Fecha',
            'Tipo',
            'Descripción',
            'Monto',
            'Registrado por',
        ];
    }

    /**
     * @param Transaccion $transaccion
     * @return array
     */
    public function map($transaccion): array
    {
        // Define qué dato va en cada columna para cada fila
        return [
            $transaccion->id,
            $transaccion->fecha,
            $transaccion->tipo,
            $transaccion->descripcion,
            $transaccion->monto,
            $transaccion->user->name,
        ];
    }
}