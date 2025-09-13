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

    // --- INICIO DE LAS MEJORAS ---

    // ACCESOR: Formatea el RUT para mostrarlo siempre con puntos y guion.
    public function getRutAttribute($value)
    {
        if (empty($value)) return null;
        try {
            return Rut::parse($value)->format();
        } catch (\Exception $e) {
            return $value; // Devuelve el valor original si hay un error de formato
        }
    }

    // MUTADOR: Limpia el RUT antes de guardarlo en la base de datos.
    public function setRutAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['rut'] = null;
            return;
        }
        $this->attributes['rut'] = Rut::parse($value)->normalize();
    }

    // ACCESOR: Atributo virtual para mostrar el teléfono con formato.
    public function getTelefonoFormattedAttribute()
    {
        $tel = $this->attributes['telefono'];
        if (empty($tel)) return null;

        // Asume que el teléfono está guardado como +569xxxxxxxx
        if (strlen($tel) === 12 && substr($tel, 0, 4) === '+569') {
            $part1 = substr($tel, 4, 4);
            $part2 = substr($tel, 8, 4);
            return "+56 9 $part1 $part2";
        }
        return $tel; // Devuelve original si no tiene el formato esperado
    }

    // MUTADOR: Limpia el Teléfono antes de guardarlo en la base de datos.
    public function setTelefonoAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['telefono'] = null;
            return;
        }
        // Guarda solo +569 y los 8 dígitos, sin espacios ni otros caracteres
        $this->attributes['telefono'] = '+569'.preg_replace('/[^0-9]/', '', substr($value, 3));
    }
    
    // --- FIN DE LAS MEJORAS ---

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }

    /**
    * Define la ruta para las notificaciones de Firebase Cloud Messaging.
    * Este método le dice a Laravel explícitamente dónde encontrar el token.
    *
    * @return string|null
    */
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

}