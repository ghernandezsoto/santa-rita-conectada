<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'titulo',
        'contenido',
        'user_id',
        'fecha_envio', // <-- AÑADIMOS ESTA LÍNEA
    ];

    /**
     * Define la relación: Un comunicado pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}