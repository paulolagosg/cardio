<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_version',
        'rut',
        'nombre',
        'correo_electronico',
        'nota',
        'asistencia'
    ];
}
