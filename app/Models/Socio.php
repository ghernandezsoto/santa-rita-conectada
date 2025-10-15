<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Freshwork\ChileanBundle\Rut;

class Socio extends Model
{
    use HasFactory, Notifiable;

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

    public function getRutAttribute($value)
    {
        if (empty($value)) return null;
        return Rut::parse($value)->format();
    }

    public function setRutAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['rut'] = null;
            return;
        }
        $this->attributes['rut'] = Rut::parse($value)->normalize();
    }

    // Este accesor ahora es más simple porque confiamos en el formato guardado.
    public function getTelefonoFormattedAttribute()
    {
        $tel = $this->attributes['telefono'];
        if (strlen($tel) === 12 && substr($tel, 0, 4) === '+569') {
            $part1 = substr($tel, 4, 4);
            $part2 = substr($tel, 8, 4);
            return "+56 9 $part1 $part2";
        }
        return $tel;
    }

    public function setTelefonoAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['telefono'] = null;
            return;
        }
        // Limpia todo excepto los números
        $digits = preg_replace('/[^0-9]/', '', $value);

        // Si después de limpiar, el número empieza con 569 y tiene 11 dígitos (56912345678)
        if (strlen($digits) === 11 && substr($digits, 0, 3) === '569') {
             $this->attributes['telefono'] = '+' . $digits;
        }
        // Si tiene 9 dígitos (912345678)
        elseif (strlen($digits) === 9 && substr($digits, 0, 1) === '9') {
            $this->attributes['telefono'] = '+56' . $digits;
        }
        // Si solo tiene 8 dígitos (12345678)
        elseif (strlen($digits) === 8) {
            $this->attributes['telefono'] = '+569' . $digits;
        }
        // Si no, guarda el valor limpio como fallback
        else {
            $this->attributes['telefono'] = $value;
        }
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }
}