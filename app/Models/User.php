<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fcm_token',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // Se añade 'socio' para que se cargue automáticamente ---
    protected $with = ['roles', 'socio'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define la ruta para las notificaciones de Firebase Cloud Messaging.
     *
     * @return string|null
     */
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    /**
     * Define la relación: Un Usuario (de login) pertenece a un Socio (de datos).
     */
    public function socio()
    {
        // La clave foránea 'socio_id' está en esta tabla (users)
        return $this->belongsTo(Socio::class);
    }

}