<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'id_region',
        'estado',
        'slug'
    ];
}
