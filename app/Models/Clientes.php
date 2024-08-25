<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'id_rubro',
        'razon_social',
        'rut',
        'giro',
        'direccion',
        'id_region',
        'id_comuna',
        'telefono',
        'sitio_web',
        'correo',
        'id_licitacion',
        'slug',
        'estado'
    ];
}
