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
    protected $table = 'transacciones';

    protected $fillable = [
        'fecha',
        'tipo',
        'monto',
        'descripcion',
        'comprobante_path',
        'user_id',
        'socio_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'datetime', // Le dice a Laravel que trate la columna 'fecha' como un objeto de fecha (Carbon).
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