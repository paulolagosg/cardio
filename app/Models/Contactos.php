<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_cliente',
        'id_tipo_contacto',
        'nombre',
        'correo_electronico',
        'telefono',
        'estado',
        'slug'
    ];
}
