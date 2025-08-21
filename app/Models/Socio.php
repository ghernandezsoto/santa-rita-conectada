<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // <-- 1. AÑADE ESTA LÍNEA

class Socio extends Model
{
    use HasFactory, Notifiable; // <-- 2. AÑADE , Notifiable AQUÍ

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rut',
        'nombre',
        'domicilio',
        'profesion',
        'edad',
        'estado_civil',
        'fecha_ingreso',
        'telefono',
        'email',
        'estado',
        'observaciones',
    ];

    // Nueva relación: Un socio puede tener muchas transacciones.
    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }
}