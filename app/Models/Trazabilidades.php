<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trazabilidades extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_cliente',
        'id_producto',
        'numero_serie',
        'slug',
        'estado',
        'factura',
        'created_at',
        'updated_at'
    ];
}
