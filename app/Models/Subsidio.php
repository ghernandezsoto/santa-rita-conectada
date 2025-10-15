<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsidio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_subsidio',
        'descripcion',
        'archivo_path',
        'monto_solicitado',
        'estado',
        'socio_id',
        'user_id',
        'observaciones_directiva',
    ];

    // Relación: Un subsidio pertenece a un socio.
    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    // Relación: Un subsidio es gestionado por un usuario (directiva).
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}