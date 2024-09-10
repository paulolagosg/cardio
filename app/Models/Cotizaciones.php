<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizaciones extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_usuario',
        'id_empresa',
        'solicitante',
        'correo_electronico',
        'razon_social',
        'rut',
        'telefono',
        'direccion',
        'giro',
        'id_region',
        'id_comuna',
        'id_vencimiento',
        'id_tipo_transporte',
        'id_tipo_pago',
        'id_plazo_pago',
        'id_tiempo_entrega',
        'observaciones',
        'fecha',
        'costo_envio'
    ];
}
