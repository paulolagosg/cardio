<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificados extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_alumno',
        'ruta',
        'codigo',
        'fecha'
    ];
}
