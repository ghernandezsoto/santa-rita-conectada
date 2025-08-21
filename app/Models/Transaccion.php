<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transacciones'; // <-- AÑADIMOS ESTA LÍNEA

    protected $fillable = [
        'fecha',
        'tipo',
        'monto',
        'descripcion',
        'comprobante_path', // <-- AÑADE ESTA LÍNEA
        'user_id',
        'socio_id', // <-- AÑADE ESTA LÍNEA
    ];

    // Nueva relación: Una transacción puede pertenecer a un socio.
    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}