<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EstadosMantencionesController;
use App\Http\Controllers\EstadosVencimientosController;
use App\Http\Controllers\MarcasController;
use App\Http\Controllers\ModelosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\RubrosController;
use App\Http\Controllers\TipoProductosController;
use App\Http\Controllers\TiposMantencionesController;
use App\Http\Controllers\TrazabilidadController;
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

    Route::get('/clientes/lista', [ClientesController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/agregar', [ClientesController::class, 'agregar'])->name('clientes.agregar');
    Route::post('/clientes/crear', [ClientesController::class, 'agregar_guardar'])->name('clientes.crear');
    Route::get('/clientes/editar/{id}', [ClientesController::class, 'editar'])->name('clientes.editar');
    Route::get('/clientes/ver/{id}', [ClientesController::class, 'ver'])->name('clientes.ver');
    Route::post('/clientes/actualizar', [ClientesController::class, 'actualizar'])->name('clientes.actualizar');
    Route::get('/clientes/eliminar/{id}', [ClientesController::class, 'eliminar'])->name('clientes.eliminar');
    Route::get('/clientes/region/{codigo}', [ClientesController::class, 'clientes_region'])->name('clientes.clientes_region');
    Route::get('/clientes/rubro/{codigo}', [ClientesController::class, 'clientes_rubro'])->name('clientes.clientes_rubro');

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
    Route::get('/trazabilidad/mantenciones/{meses?}', [TrazabilidadController::class, 'mantenciones'])->name('trazabilidad.mantenciones');

    //Route::get('/pdf', [TrazabilidadController::class, 'pdf'])->name('trazabilidad.pdf');
    Route::get('perfil/ver/{id?}', [PerfilController::class, 'index'])->name('perfil.ver');
    Route::post('perfil/editar', [PerfilController::class, 'editar'])->name('perfil.editar');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
