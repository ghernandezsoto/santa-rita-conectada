<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_documento',
        'descripcion',
        'fecha_documento',
        'archivo_path',
        'user_id',
    ];

    /**
     * Get the user that uploaded the document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}