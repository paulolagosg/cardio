<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versiones extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nombre',
        'id_cliente',
        'id_curso',
        'id_modalidad',
        'fecha_version',
        'id_usuario_instructor',
        'id_usuario_firmante',
        'firma',
        'ruta',
        'horas',
        'contraparte',
        'rut',
        'nombre',
        'correo_electronico',
        'telefono',
        'fecha_certificado',
        'slug',
        'estado'
    ];
}
