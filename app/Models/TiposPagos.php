<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposPagos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'estado',
        'slug'
    ];
}
