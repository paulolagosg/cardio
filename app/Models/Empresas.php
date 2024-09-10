<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'rut',
        'razon_social',
        'giro',
        'correo_electronico',
        'direccion',
        'telefono',
        'sitio_web',
        'logo',
        'id_banco',
        'id_tipo_cuenta',
        'numero_cuenta',
        'ruta'
    ];
}
