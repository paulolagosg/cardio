<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionesProductos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_cotizacion',
        'id_producto',
        'precio',
        'cantidad',
        'descuento'
    ];
}
