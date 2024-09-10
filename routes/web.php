<?php

use App\Http\Controllers\BancosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\CotizacionesController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\EstadosCotizacionesController;
use App\Http\Controllers\EstadosMantencionesController;
use App\Http\Controllers\EstadosVencimientosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarcasController;
use App\Http\Controllers\ModelosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PlazosPagosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\RubrosController;
use App\Http\Controllers\TiemposEntregasController;
use App\Http\Controllers\TipoProductosController;
use App\Http\Controllers\TiposMantencionesController;
use App\Http\Controllers\TiposPagosController;
use App\Http\Controllers\TiposTransportesController;
use App\Http\Controllers\TrazabilidadController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VencimientosController;
use App\Http\Middleware\CheckUsuarioActivo;
use App\Models\TiposTransportes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/rubros/lista', [RubrosController::class, 'index'])->name('rubros.index');
    Route::get('/rubros/agregar', [RubrosController::class, 'agregar'])->name('rubros.agregar');
    Route::post('/rubros/crear', [RubrosController::class, 'agregar_guardar'])->name('rubros.crear');
    Route::get('/rubros/editar/{id}', [RubrosController::class, 'editar'])->name('rubros.editar');
    Route::post('/rubros/actualizar', [RubrosController::class, 'actualizar'])->name('rubros.actualizar');
    Route::get('/rubros/eliminar/{id}', [RubrosController::class, 'eliminar'])->name('rubros.eliminar');

    Route::get('/estados_vencimientos/lista', [EstadosVencimientosController::class, 'index'])->name('estados_vencimientos.index');
    Route::get('/estados_vencimientos/agregar', [EstadosVencimientosController::class, 'agregar'])->name('estados_vencimientos.agregar');
    Route::post('/estados_vencimientos/crear', [EstadosVencimientosController::class, 'agregar_guardar'])->name('estados_vencimientos.crear');
    Route::get('/estados_vencimientos/editar/{id}', [EstadosVencimientosController::class, 'editar'])->name('estados_vencimientos.editar');
    Route::post('/estados_vencimientos/actualizar', [EstadosVencimientosController::class, 'actualizar'])->name('estados_vencimientos.actualizar');
    Route::get('/estados_vencimientos/eliminar/{id}', [EstadosVencimientosController::class, 'eliminar'])->name('estados_vencimientos.eliminar');

    Route::get('/estados_mantenciones/lista', [EstadosMantencionesController::class, 'index'])->name('estados_mantenciones.index');
    Route::get('/estados_mantenciones/agregar', [EstadosMantencionesController::class, 'agregar'])->name('estados_mantenciones.agregar');
    Route::post('/estados_mantenciones/crear', [EstadosMantencionesController::class, 'agregar_guardar'])->name('estados_mantenciones.crear');
    Route::get('/estados_mantenciones/editar/{id}', [EstadosMantencionesController::class, 'editar'])->name('estados_mantenciones.editar');
    Route::post('/estados_mantenciones/actualizar', [EstadosMantencionesController::class, 'actualizar'])->name('estados_mantenciones.actualizar');
    Route::get('/estados_mantenciones/eliminar/{id}', [EstadosMantencionesController::class, 'eliminar'])->name('estados_mantenciones.eliminar');

    Route::get('/tipos_productos/lista', [TipoProductosController::class, 'index'])->name('tipos_productos.index');
    Route::get('/tipos_productos/agregar', [TipoProductosController::class, 'agregar'])->name('tipos_productos.agregar');
    Route::post('/tipos_productos/crear', [TipoProductosController::class, 'agregar_guardar'])->name('tipos_productos.crear');
    Route::get('/tipos_productos/editar/{id}', [TipoProductosController::class, 'editar'])->name('tipos_productos.editar');
    Route::post('/tipos_productos/actualizar', [TipoProductosController::class, 'actualizar'])->name('tipos_productos.actualizar');
    Route::get('/tipos_productos/eliminar/{id}', [TipoProductosController::class, 'eliminar'])->name('tipos_productos.eliminar');
    Route::get('/tipos_productos/obtener/{id}', [TipoProductosController::class, 'obtener_productos'])->name('tipos_productos.obtener_productos');

    Route::get('/tipos_mantenciones/lista', [TiposMantencionesController::class, 'index'])->name('tipos_mantenciones.index');
    Route::get('/tipos_mantenciones/agregar', [TiposMantencionesController::class, 'agregar'])->name('tipos_mantenciones.agregar');
    Route::post('/tipos_mantenciones/crear', [TiposMantencionesController::class, 'agregar_guardar'])->name('tipos_mantenciones.crear');
    Route::get('/tipos_mantenciones/editar/{id}', [TiposMantencionesController::class, 'editar'])->name('tipos_mantenciones.editar');
    Route::post('/tipos_mantenciones/actualizar', [TiposMantencionesController::class, 'actualizar'])->name('tipos_mantenciones.actualizar');
    Route::get('/tipos_mantenciones/eliminar/{id}', [TiposMantencionesController::class, 'eliminar'])->name('tipos_mantenciones.eliminar');
    Route::get('/tipos_mantenciones/obtener/{id}', [TiposMantencionesController::class, 'obtener_mantenciones'])->name('tipos_mantenciones.obtener_mantenciones');

    Route::get('/marcas/lista', [MarcasController::class, 'index'])->name('marcas.index');
    Route::get('/marcas/agregar', [MarcasController::class, 'agregar'])->name('marcas.agregar');
    Route::post('/marcas/crear', [MarcasController::class, 'agregar_guardar'])->name('marcas.crear');
    Route::get('/marcas/editar/{id}', [MarcasController::class, 'editar'])->name('marcas.editar');
    Route::post('/marcas/actualizar', [MarcasController::class, 'actualizar'])->name('marcas.actualizar');
    Route::get('/marcas/eliminar/{id}', [MarcasController::class, 'eliminar'])->name('marcas.eliminar');

    Route::get('/modelos/lista', [ModelosController::class, 'index'])->name('modelos.index');
    Route::get('/modelos/agregar', [ModelosController::class, 'agregar'])->name('modelos.agregar');
    Route::post('/modelos/crear', [ModelosController::class, 'agregar_guardar'])->name('modelos.crear');
    Route::get('/modelos/editar/{id}', [ModelosController::class, 'editar'])->name('modelos.editar');
    Route::post('/modelos/actualizar', [ModelosController::class, 'actualizar'])->name('modelos.actualizar');
    Route::get('/modelos/eliminar/{id}', [ModelosController::class, 'eliminar'])->name('modelos.eliminar');
    Route::get('/modelos/{id}', [ModelosController::class, 'obtener_modelos'])->name('modelos.obtener_modelos');

    Route::get('/productos/lista', [ProductosController::class, 'index'])->name('productos.index');
    Route::get('/productos/agregar', [ProductosController::class, 'agregar'])->name('productos.agregar');
    Route::post('/productos/crear', [ProductosController::class, 'agregar_guardar'])->name('productos.crear');
    Route::get('/productos/editar/{id}', [ProductosController::class, 'editar'])->name('productos.editar');
    Route::post('/productos/actualizar', [ProductosController::class, 'actualizar'])->name('productos.actualizar');
    Route::get('/productos/eliminar/{id}', [ProductosController::class, 'eliminar'])->name('productos.eliminar');
    Route::get('/productos/obtener_precio/{id}', [ProductosController::class, 'obtener_precio'])->name('productos.obtener_precio');

    Route::get('/clientes/lista', [ClientesController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/agregar', [ClientesController::class, 'agregar'])->name('clientes.agregar');
    Route::post('/clientes/crear', [ClientesController::class, 'agregar_guardar'])->name('clientes.crear');
    Route::get('/clientes/editar/{id}', [ClientesController::class, 'editar'])->name('clientes.editar');
    Route::get('/clientes/ver/{id}', [ClientesController::class, 'ver'])->name('clientes.ver');
    Route::post('/clientes/actualizar', [ClientesController::class, 'actualizar'])->name('clientes.actualizar');
    Route::get('/clientes/eliminar/{id}', [ClientesController::class, 'eliminar'])->name('clientes.eliminar');
    Route::get('/clientes/region/{codigo}', [ClientesController::class, 'clientes_region'])->name('clientes.clientes_region');
    Route::get('/clientes/rubro/{codigo}', [ClientesController::class, 'clientes_rubro'])->name('clientes.clientes_rubro');
    Route::get('/clientes/comuna/{region}', [ClientesController::class, 'clientes_comunas'])->name('clientes.clientes_comunas');
    Route::get('/clientes/comuna_detalle/{region}', [ClientesController::class, 'clientes_comunas_detalle'])->name('clientes.clientes_comunas_detalle');
    Route::post('/clientes/guardar_secundario', [ClientesController::class, 'guardar_secundario'])->name('clientes.guardar_secundario');
    Route::get('/clientes/eliminar_contacto/{id}', [ClientesController::class, 'eliminar_contacto'])->name('clientes.eliminar_contacto');

    Route::get('/comunas/{id}', [ClientesController::class, 'obtener_comunas'])->name('clientes.obtener_comunas');

    Route::get('/trazabilidad/lista', [TrazabilidadController::class, 'index'])->name('trazabilidad.index');
    Route::get('/trazabilidad/agregar', [TrazabilidadController::class, 'agregar'])->name('trazabilidad.agregar');
    Route::get('/trazabilidad/agregar_sc', [TrazabilidadController::class, 'agregar_sc'])->name('trazabilidad.agregar_sc');
    Route::post('/trazabilidad/crear', [TrazabilidadController::class, 'agregar_guardar'])->name('trazabilidad.crear');
    Route::post('/trazabilidad/crear_sc', [TrazabilidadController::class, 'agregar_guardar_sc'])->name('trazabilidad.crear_sc');
    Route::get('/trazabilidad/editar/{id}', [TrazabilidadController::class, 'editar'])->name('trazabilidad.editar');
    Route::post('/trazabilidad/actualizar', [TrazabilidadController::class, 'actualizar'])->name('trazabilidad.actualizar');
    Route::get('/trazabilidad/eliminar/{id}', [TrazabilidadController::class, 'eliminar'])->name('trazabilidad.eliminar');
    Route::get('/trazabilidad/dispositivos/{id}', [TrazabilidadController::class, 'obtener_dispositivos'])->name('trazabilidad.dispositivos');
    Route::get('/trazabilidad/cambiar_estado/{id}', [TrazabilidadController::class, 'cambiar_estado'])->name('trazabilidad.cambiar_estado');
    Route::get('/trazabilidad/cambiar_estado_mantencion/{id}', [TrazabilidadController::class, 'cambiar_estado_mantencion'])->name('trazabilidad.cambiar_estado_mantencion');
    Route::post('/trazabilidad/guardar_dispositivo', [TrazabilidadController::class, 'guardar_dispositivo'])->name('trazabilidad.guardar_dispositivo');
    Route::post('/trazabilidad/guardar_mantencion', [TrazabilidadController::class, 'guardar_mantencion'])->name('trazabilidad.guardar_mantencion');
    Route::get('/trazabilidad/vencimientos/{meses?}', [TrazabilidadController::class, 'vencimientos'])->name('trazabilidad.vencimientos');
    Route::get('/trazabilidad/clientes/{cliente}', [TrazabilidadController::class, 'clientes'])->name('trazabilidad.clientes');

    Route::get('/pdf', [TrazabilidadController::class, 'pdf'])->name('trazabilidad.pdf');

    //enviar correo con alertas
    Route::get('alertas/enviar_correo', [HomeController::class, 'enviar_correo'])->name('alertas.enviar_correo');

    //perfil de usuario
    Route::get('perfil/ver/{id?}', [PerfilController::class, 'index'])->name('perfil.ver');
    Route::post('perfil/editar', [PerfilController::class, 'editar'])->name('perfil.editar');


    /*** inicio cotizaciones */
    /*
    Route::get('/bancos/lista', [BancosController::class, 'index'])->name('bancos.index');
    Route::get('/bancos/agregar', [BancosController::class, 'agregar'])->name('bancos.agregar');
    Route::post('/bancos/crear', [BancosController::class, 'agregar_guardar'])->name('bancos.crear');
    Route::get('/bancos/editar/{id}', [BancosController::class, 'editar'])->name('bancos.editar');
    Route::post('/bancos/actualizar', [BancosController::class, 'actualizar'])->name('bancos.actualizar');
    Route::get('/bancos/eliminar/{id}', [BancosController::class, 'eliminar'])->name('bancos.eliminar');

    Route::get('/vencimientos/lista', [VencimientosController::class, 'index'])->name('vencimientos.index');
    Route::get('/vencimientos/agregar', [VencimientosController::class, 'agregar'])->name('vencimientos.agregar');
    Route::post('/vencimientos/crear', [VencimientosController::class, 'agregar_guardar'])->name('vencimientos.crear');
    Route::get('/vencimientos/editar/{id}', [VencimientosController::class, 'editar'])->name('vencimientos.editar');
    Route::post('/vencimientos/actualizar', [VencimientosController::class, 'actualizar'])->name('vencimientos.actualizar');
    Route::get('/vencimientos/eliminar/{id}', [VencimientosController::class, 'eliminar'])->name('vencimientos.eliminar');

    Route::get('/tipos_transportes/lista', [TiposTransportesController::class, 'index'])->name('tipos_transportes.index');
    Route::get('/tipos_transportes/agregar', [TiposTransportesController::class, 'agregar'])->name('tipos_transportes.agregar');
    Route::post('/tipos_transportes/crear', [TiposTransportesController::class, 'agregar_guardar'])->name('tipos_transportes.crear');
    Route::get('/tipos_transportes/editar/{id}', [TiposTransportesController::class, 'editar'])->name('tipos_transportes.editar');
    Route::post('/tipos_transportes/actualizar', [TiposTransportesController::class, 'actualizar'])->name('tipos_transportes.actualizar');
    Route::get('/tipos_transportes/eliminar/{id}', [TiposTransportesController::class, 'eliminar'])->name('tipos_transportes.eliminar');

    Route::get('/formas_pago/lista', [TiposPagosController::class, 'index'])->name('formas_pago.index');
    Route::get('/formas_pago/agregar', [TiposPagosController::class, 'agregar'])->name('formas_pago.agregar');
    Route::post('/formas_pago/crear', [TiposPagosController::class, 'agregar_guardar'])->name('formas_pago.crear');
    Route::get('/formas_pago/editar/{id}', [TiposPagosController::class, 'editar'])->name('formas_pago.editar');
    Route::post('/formas_pago/actualizar', [TiposPagosController::class, 'actualizar'])->name('formas_pago.actualizar');
    Route::get('/formas_pago/eliminar/{id}', [TiposPagosController::class, 'eliminar'])->name('formas_pago.eliminar');

    Route::get('/plazos_pagos/lista', [PlazosPagosController::class, 'index'])->name('plazos_pagos.index');
    Route::get('/plazos_pagos/agregar', [PlazosPagosController::class, 'agregar'])->name('plazos_pagos.agregar');
    Route::post('/plazos_pagos/crear', [PlazosPagosController::class, 'agregar_guardar'])->name('plazos_pagos.crear');
    Route::get('/plazos_pagos/editar/{id}', [PlazosPagosController::class, 'editar'])->name('plazos_pagos.editar');
    Route::post('/plazos_pagos/actualizar', [PlazosPagosController::class, 'actualizar'])->name('plazos_pagos.actualizar');
    Route::get('/plazos_pagos/eliminar/{id}', [PlazosPagosController::class, 'eliminar'])->name('plazos_pagos.eliminar');

    Route::get('/tiempos_entregas/lista', [TiemposEntregasController::class, 'index'])->name('tiempos_entregas.index');
    Route::get('/tiempos_entregas/agregar', [TiemposEntregasController::class, 'agregar'])->name('tiempos_entregas.agregar');
    Route::post('/tiempos_entregas/crear', [TiemposEntregasController::class, 'agregar_guardar'])->name('tiempos_entregas.crear');
    Route::get('/tiempos_entregas/editar/{id}', [TiemposEntregasController::class, 'editar'])->name('tiempos_entregas.editar');
    Route::post('/tiempos_entregas/actualizar', [TiemposEntregasController::class, 'actualizar'])->name('tiempos_entregas.actualizar');
    Route::get('/tiempos_entregas/eliminar/{id}', [TiemposEntregasController::class, 'eliminar'])->name('tiempos_entregas.eliminar');

    Route::get('/usuarios/lista', [UsersController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/agregar', [UsersController::class, 'agregar'])->name('usuarios.agregar');
    Route::post('/usuarios/crear', [UsersController::class, 'agregar_guardar'])->name('usuarios.crear');
    Route::get('/usuarios/editar/{id}', [UsersController::class, 'editar'])->name('usuarios.editar');
    Route::post('/usuarios/actualizar', [UsersController::class, 'actualizar'])->name('usuarios.actualizar');
    Route::get('/usuarios/eliminar/{id}', [UsersController::class, 'eliminar'])->name('usuarios.eliminar');

    Route::get('/empresas/lista', [EmpresasController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/agregar', [EmpresasController::class, 'agregar'])->name('empresas.agregar');
    Route::post('/empresas/crear', [EmpresasController::class, 'agregar_guardar'])->name('empresas.crear');
    Route::get('/empresas/editar/{id}', [EmpresasController::class, 'editar'])->name('empresas.editar');
    Route::post('/empresas/actualizar', [EmpresasController::class, 'actualizar'])->name('empresas.actualizar');
    Route::get('/empresas/eliminar/{id}', [EmpresasController::class, 'eliminar'])->name('empresas.eliminar');

    Route::get('/cotizaciones/lista', [CotizacionesController::class, 'index'])->name('cotizaciones.index');
    Route::get('/cotizaciones/agregar', [CotizacionesController::class, 'agregar'])->name('cotizaciones.agregar');
    Route::post('/cotizaciones/crear', [CotizacionesController::class, 'agregar_guardar'])->name('cotizaciones.crear');
    Route::get('/cotizaciones/pdf/{id}', [CotizacionesController::class, 'cotizacion_pdf'])->name('cotizaciones.pdf');
    Route::get('/cotizaciones/enviar_pdf/{id}', [CotizacionesController::class, 'cotizacion_enviar_pdf'])->name('cotizaciones.enviar_pdf');
    Route::get('/cotizaciones/editar/{id}', [CotizacionesController::class, 'editar'])->name('cotizaciones.editar');
    Route::post('/cotizaciones/actualizar', [CotizacionesController::class, 'actualizar'])->name('cotizaciones.actualizar');
    Route::get('/cotizaciones/eliminar/{id}', [CotizacionesController::class, 'eliminar'])->name('cotizaciones.eliminar');
    Route::get('/cotizaciones/cambiar_estado/{id}/{estado}', [CotizacionesController::class, 'cambiar_estado'])->name('cotizaciones.cambiar_estado');

    Route::get('/estados_cotizaciones/lista', [EstadosCotizacionesController::class, 'index'])->name('estados_cotizaciones.index');
    Route::get('/estados_cotizaciones/agregar', [EstadosCotizacionesController::class, 'agregar'])->name('estados_cotizaciones.agregar');
    Route::post('/estados_cotizaciones/crear', [EstadosCotizacionesController::class, 'agregar_guardar'])->name('estados_cotizaciones.crear');
    Route::get('/estados_cotizaciones/editar/{id}', [EstadosCotizacionesController::class, 'editar'])->name('estados_cotizaciones.editar');
    Route::post('/estados_cotizaciones/actualizar', [EstadosCotizacionesController::class, 'actualizar'])->name('estados_cotizaciones.actualizar');
    Route::get('/estados_cotizaciones/eliminar/{id}', [EstadosCotizacionesController::class, 'eliminar'])->name('estados_cotizaciones.eliminar');
    */
    /*** fin cotizaciones */
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
