@extends('adminlte::page')

@section('title', 'Cotizaciones - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Cotizaciones</span> <span> Modificar</span>
@stop

@section('content')
<div class="card  card-dark">
    <div class="card-body">
        <div class="row">
            @if (session()->has('error'))
            <div class="alert alert-danger mt-3 ml-3" style="display: block;">
                {{ session('error') }}
            </div>
            @endif
            @if (session()->has('message'))
            <div class="alert alert-success mt-3 ml-3" style="display: block;">
                {{ session('message') }}
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="post" id="fCotizacion" action="{{route('cotizaciones.actualizar')}}" enctype="multipart/form-data">
                    <div class="card card-dark">
                        <div class="card-header">
                        </div>
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Ejecutivo</label>
                                        <select class="form-control select2" id="id_usuario" name="id_usuario" required style="width:100%">
                                            <option value="">Seleccione un ejecutivo</option>
                                            @foreach($usuarios as $usuario)
                                            <option value="{{$usuario->id}}" {{old('id_usuario') == $usuario->id
                                                ? 'selected' : ''}} {{$datos->id == $usuario->id
                                                    ? 'selected' : ''}}>{{$usuario->name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_ejecutivo" class="error">Debe seleccionar un ejecutivo</span>
                                        <input type="hidden" name="id" id="id" value="{{$datos->id}}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Empresa</label>
                                        <select class="form-control select2" id="id_empresa" name="id_empresa" required style="width:100%">
                                            <option value="">Seleccione una empresa</option>
                                            @foreach($empresas as $e)
                                            <option value="{{$e->id}}" {{old('id_empresa') == $e->id
                                                ? 'selected' : ''}} {{$datos->id_empresa == $e->id
                                                    ? 'selected' : ''}}>{{$e->razon_social}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_empresa" class="error">Debe seleccionar una empresa</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Solicitante</label>
                                        <input type="text" class="form-control" id="solicitante" name="solicitante" value="{{$datos->solicitante}}" required />
                                        <span id="error_solicitante" class="error">Debe ingresar un solicitante</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="{{$datos->correo_electronico}}" required />
                                        <span id="error_correo" class="error">Debe ingresar un correo electrónico válido</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Razón Social</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón social" value="{{old('razon_social',$datos->razon_social)}}" required>
                                        <span id="error_rz" class="error">Debe ingresar una razon social</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="nombre">RUT Empresa</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="rut" name="rut" placeholder="99999999-9" value="{{old('rut',$datos->rut)}}" required>
                                        <span id="error_rut" class="error">Debe ingresar un RUT</span>
                                        <span id="error_rut_val" class="error">RUT no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Giro</label> <label class="obligatorio">*</label>
                                        <input class="form-control" type="text" id="giro" name="giro" value="{{old('giro',$datos->giro)}}" placeholder="Giro" />
                                        <span id="error_giro" class="error">Debe ingresar un giro</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Teléfono</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="999999999" value="{{old('telefono',$datos->telefono)}}" required>
                                        <span id="error_telefono" class="error">Debe ingresar un teléfono</span>
                                        <span id="error_telefono_val" class="error">Debe ingresar un teléfono válido</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" value="{{old('direccion',$datos->direccion)}}" required>
                                        <span id="error_direccion" class="error">Debe ingresar una dirección</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Región</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_region" name="id_region" required style="width:100%" onchange="obtener_comunas(this.value)">
                                            <option value="">Seleccione una región</option>
                                            @foreach($regiones as $region)
                                            <option value="{{$region->id}}" {{old('id_region') == $region->id
                                                ? 'selected' : ''}} {{$datos->id_region == $region->id
                                                    ? 'selected' : ''}}>{{$region->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_region" class="error">Debe seleccionar una región</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Comuna</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_comuna" name="id_comuna" required style="width:100%">
                                            <option value="">Seleccione una región</option>
                                        </select>
                                        <span id="error_comuna" class="error">Debe seleccionar una comuna</span>
                                        <input type="hidden" id="hidden_comuna" value="{{$datos->id_comuna}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row p-2 table-responsive">
                                <div class="col-md-12">
                                    <button type="button" class="btn bg-dark" onclick="agregar_producto_cotizacion()"><i class="fa fa-plus-circle"></i> Agregar Producto</button>
                                    <table id="tablaParametros" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Producto</th>
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-center">Precio Normal</th>
                                                <th class="text-center">Descuento x Unidad (%)</th>
                                                <th class="text-center">Descuento x Unidad ($)</th>
                                                <th class="text-center">Precio Unitario</th>
                                                <th class="text-center">Subtotal</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            @foreach($productos_cotizacion as $producto)
                                            <tr>
                                                <td>{{$producto->nombre}}
                                                    <input type="hidden" name="id_producto[]" id="id_producto[]" value="{{$producto->id_producto}}" />
                                                    <input type="hidden" name="cantidad[]" id="cantidad[]" value="{{$producto->cantidad}}" />
                                                    <input type="hidden" name="descuento[]" id="descuento[]" value="{{$producto->descuento}}" />
                                                    <input type="hidden" name="descuento_pesos[]" id="descuento_pesos[]" value="{{$producto->descuento_pesos}}" />
                                                    <input type="hidden" name="precio[]" id="precio[]" value="{{$producto->precio}}" />
                                                    <input type="hidden" name="unitario[]" id="unitario[]" value="{{$producto->unitario}}" />
                                                    <input type="hidden" name="subtotal[]" id="subtotal[]" value="{{$producto->subtotal}}" />
                                                </td>
                                                <td class="text-right">{{$producto->cantidad}}</td>
                                                <td class="text-right">$ {{number_format($producto->precio, 0, ',', '.')}}</td>
                                                <td class="text-right">{{$producto->descuento}}</td>
                                                <td class="text-right">$ {{number_format($producto->descuento_pesos, 0, ',', '.')}}</td>
                                                <td class="text-right">$ {{number_format($producto->unitario, 0, ',', '.')}}</td>
                                                <td class="text-right">$ {{number_format($producto->subtotal, 0, ',', '.')}}</td>
                                                <td class="text-center">
                                                    <a href="#tablaParametros" onclick="eliminarFila(this)"><i class="fa fa-trash text-danger"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach


                                            @if(old('filas'))
                                            @foreach(old('filas') as $fila)
                                            <tr>
                                                <td class="text-left">{!! $fila['col1'] !!}</td>
                                                <td class="text-right">{!! $fila['col2'] !!}</td>
                                                <td class="text-right">{!! $fila['col3'] !!}</td>
                                                <td class="text-right">{!! $fila['col4'] !!}</td>
                                                <td class="text-right">{!! $fila['col5'] !!}</td>
                                                <td class="text-right">{!! $fila['col6'] !!}</td>
                                                <td class="text-right">{!! $fila['col7'] !!}</td>
                                                <td class="text-center">{!! $fila['col8'] !!}</td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfooter>
                                            <tr class="text-right">
                                                <th>Totales</th>
                                                <th id="tc2"></th>
                                                <th id="tc3"></th>
                                                <th id="tc4"></th>
                                                <th id="tc5"></th>
                                                <th id="tc6"></th>
                                                <th id="tc7"></th>
                                                <th></th>
                                            </tr>
                                        </tfooter>
                                    </table>
                                    <span id="error_cantidad_filas" class="error">Debe agregar al menos un producto</span>
                                    <div id="filasOcultas">
                                        @if(old('filas'))
                                        @foreach(old('filas') as $fila)
                                        <input type="hidden" name="filas[{{ $loop->index }}][col1]" value="{{ $fila['col1'] }}">
                                        <input type="hidden" name="filas[{{ $loop->index }}][col2]" value="{{ $fila['col2'] }}">
                                        <input type="hidden" name="filas[{{ $loop->index }}][col3]" value="{{ $fila['col3'] }}">
                                        <input type="hidden" name="filas[{{ $loop->index }}][col4]" value="{{ $fila['col4'] }}">
                                        <input type="hidden" name="filas[{{ $loop->index }}][col5]" value="{{ $fila['col5'] }}">
                                        <input type="hidden" name="filas[{{ $loop->index }}][col6]" value="{{ $fila['col6'] }}">
                                        <input type="hidden" name="filas[{{ $loop->index }}][col7]" value="{{ $fila['col7'] }}">
                                        <input type="hidden" name="filas[{{ $loop->index }}][col8]" value="{{ $fila['col8'] }}">
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vencimiento">Validez Cotización</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_vencimiento" name="id_vencimiento" required style="width:100%">
                                            <option value="">Seleccione una opción</option>
                                            @foreach($vencimientos as $vencimiento)
                                            <option value="{{$vencimiento->id}}" {{old('id_vencimiento') ==
                                            $vencimiento->id ? 'selected' : ''}} {{$datos->id_vencimiento ==
                                                $vencimiento->id ? 'selected' : ''}}>{{$vencimiento->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_vencimiento" class="error">Debe seleccionar una opción</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total">Envío</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_tipo_transporte" name="id_tipo_transporte" required style="width:100%" onchange="habilita_costo(this.value)">
                                            <option value="">Seleccione una opción</option>
                                            @foreach($tipos_transporte as $t)
                                            <option value="{{$t->id}}" {{old('id_tipo_transporte') == $t->id ? 'selected' : ''}} {{$datos->id_tipo_transporte == $t->id ? 'selected' : ''}}>{{$t->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_tipo_transporte" class="error">Debe seleccionar una opción</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="total">Costo Envío</label>
                                        <input type="number" class="form-control" id="costo_envio" name="costo_envio" value="{{old('costo_envio',$datos->costo_envio)}}" @if($datos->costo_envio
                                        <= 0) disabled @endif />
                                        <span id="error_costo_envio" class="error">Ingrese un costo de envío válido</span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total">Forma de Pago</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_tipo_pago" name="id_tipo_pago" required>
                                            <option value="">Seleccione una opción</option>
                                            @foreach($formas_pago as $t)
                                            <option value="{{$t->id}}" {{old('id_tipo_pago') ==$t->id ? 'selected' : ''}}{{$datos->id_tipo_pago == $t->id ? 'selected' : ''}}>{{$t->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_tipo_pago" class="error">Debe seleccionar una opción</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total">Plazo de Pago</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_plazo_pago" name="id_plazo_pago" required>
                                            <option value="">Seleccione una opción</option>
                                            @foreach($plazos_pago as $t)
                                            <option value="{{$t->id}}" {{old('id_plazo_pago') ==$t->id ? 'selected' : ''}} {{$datos->id_plazo_pago ==$t->id ? 'selected' : ''}}>{{$t->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_plazo_pago" class="error">Debe seleccionar una opción</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total">Tiempo de Entrega</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_tiempo_entrega" name="id_tiempo_entrega" required>
                                            <option value="">Seleccione una opción</option>
                                            @foreach($tiempos_entrega as $t)
                                            <option value="{{$t->id}}" {{old('id_tiempo_entrega') ==$t->id ? 'selected' : ''}} {{$datos->id_tiempo_entrega ==$t->id ? 'selected' : ''}}>{{$t->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_tiempo" class="error">Debe seleccionar una opción</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="total">Observaciones</label>
                                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{$datos->observaciones}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <button type="button" onclick="validar_cotizacion()" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                                <a href="{{route('cotizaciones.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- modal agregar producto -->
<div class="modal fade" id="modal-agregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="fModal" action="{{route('trazabilidad.guardar_dispositivo')}}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Producto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bodyModal">

                    <div class="row">
                        <div id="msgError" class="alert alert-danger" style="display: none;">
                        </div>
                        <div id="msgOK" class="alert alert-success" style="display: none;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nombre">Producto</label>
                                <select class="form-control select2" id="id_producto" name="id_producto" style="width:100%" required onchange="obtener_precio(this.value)">
                                    <option value="">Seleccione un producto</option>
                                    @foreach($productos as $producto)
                                    <option value="{{$producto->id}}">{{$producto->nombre}}</option>
                                    @endforeach
                                </select>
                                <span id="error_producto_m" class="error">Debe seleccionar un producto</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" value="" required>
                                <span id="error_cantidad_m" class="error">Debe ingresar una canidad válida</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Precio Normal</label>
                                <input type="number" class="form-control" id="precio" name="precio" value="" required>
                                <span id="error_precio_m" class="error">Debe ingresar un precio válido</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">% Descuento x Unidad</label>
                                <input type="number" class="form-control" id="descuento" name="descuento" value="" required>
                                <span id="error_descuento_m" class="error">Debe ingresar un descuento válido</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-right">
                    <button type="button" onclick="agregar_producto_grilla()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                    <button type="button" class="btn bg-dark" data-dismiss="modal"><i class="fa fa-ban"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('css')
<style>
    .error {
        color: #87161b;
        font-size: 14px;
        display: none;
    }

    .obligatorio {
        color: red;
        font-size: 14px;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

@stop

@section('js')
<script src="/vendor/adminlte/dist/js/functions.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: 'resolve'
        })
    });

    $(function() {
        $("#rut").rut().on('rutValido', function(e, rut, dv) {
            alert("El rut " + rut + "-" + dv + " es correcto");
        }, {
            minimumLength: 7
        });
    })

    function agregar_producto_cotizacion() {
        $('#modal-agregar').modal('show');
        $('#descuento').val(0);
        $('#cantidad').val(0);
    }
    let filaIndex = document.getElementById('tablaParametros').getElementsByTagName('tbody')[0].rows.length;

    function agregar_producto_grilla() {
        $('#error_producto_m').hide()
        $('#error_cantidad_m').hide();
        $('#error_precio_m').hide();
        $('#error_descuento_m').hide();
        var data = $('#id_producto').select2('data')
        var producto = data[0].text;
        var cantidad = $('#cantidad').val();
        var precio = $('#precio').val();
        var descuento = $('#descuento').val();
        var descuento_pesos = 0;
        var unitario = precio;

        var errores = 0;
        if (data[0].id == "") {
            errores++;
            $('#error_producto_m').show();
        }
        if (parseInt(cantidad) <= 0) {
            errores++;
            $('#error_cantidad_m').show();
        }
        if (parseInt(precio) <= 0 || precio == "") {
            errores++;
            $('#error_precio_m').show();
        }
        if (parseInt(descuento) < 0 || parseInt(descuento) > 100) {
            errores++;
            $('#error_descuento_m').show();
        }
        const formatoCLP = (valor) => {
            return valor.toLocaleString('es-CL', {
                style: 'currency',
                currency: 'CLP'
            });
        };

        const formatterPeso = new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        });

        if (errores == 0) {
            if (parseInt(descuento) > 0) {
                descuento_pesos = Math.ceil(parseInt(precio) * (descuento / 100));
                unitario = Math.ceil(precio - descuento_pesos);
            }
            var subtotal = parseFloat(cantidad) * parseFloat(unitario);
            var fila = '<tr><td class="text-left"><input type="hidden" name="id_producto[]" id="id_producto[]" value="' + data[0].id + '"  /><input type="hidden" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"  /><input type="hidden" name="descuento[]" id="descuento[]" value="' + descuento + '"  /><input type="hidden" name="descuento_pesos[]" id="descuento_pesos[]" value="' + descuento_pesos + '"  /><input type="hidden" name="precio[]" id="precio[]" value="' + precio + '"  /><input type="hidden" name="unitario[]" id="unitario[]" value="' + unitario + '"  /><input type="hidden" name="subtotal[]" id="subtotal[]" value="' + subtotal + '"  />' +
                producto + '</td><td class="text-right">' + formatoCLP(cantidad) + '</td><td class="text-right">' + formatoCLP(precio) + '</td><td class="text-right">' + descuento + '%</td><td class="text-right">' + formatoCLP(descuento_pesos) + '</td><td class="text-right">' + formatoCLP(unitario) + '</td><td class="text-right">' + formatoCLP(subtotal) + '</td><td class="text-center"><a href="#tablaParametros" onclick="eliminarFila(this, ' + filaIndex + ')"><i class="fa fa-trash text-danger"></a></td></tr>';
            jQuery('#tbody').append(fila);

            let tabla = document.getElementById('tablaParametros').getElementsByTagName('tbody')[0];

            let filasOcultas = document.getElementById('filasOcultas');
            let nuevaPos = tabla.rows.length - 1;

            filasOcultas.innerHTML += `
            <input type="hidden" name="filas[${nuevaPos}][col1]" value='<input type="hidden" name="id_producto[]" id="id_producto[]" value="${data[0].id}"  /><input type="hidden" name="cantidad[]" id="cantidad[]" value="${cantidad}"  /><input type="hidden" name="descuento[]" id="descuento[]" value="${descuento}"  /><input type="hidden" name="descuento_pesos[]" id="descuento_pesos[]" value="${descuento_pesos}"  /><input type="hidden" name="precio[]" id="precio[]" value="${precio}"  /><input type="hidden" name="unitario[]" id="unitario[]" value="${unitario}"  /><input type="hidden" name="subtotal[]" id="subtotal[]" value="${subtotal}"  /> ${producto}' >
            <input type="hidden" name="filas[${nuevaPos}][col2]" value="${(cantidad)}">
            <input type="hidden" name="filas[${nuevaPos}][col3]" value="${formatoCLP(precio)}">
            <input type="hidden" name="filas[${nuevaPos}][col4]" value="${descuento}">
            <input type="hidden" name="filas[${nuevaPos}][col5]" value="${formatoCLP(descuento_pesos)}">
            <input type="hidden" name="filas[${nuevaPos}][col6]" value="${formatoCLP(unitario)}">
            <input type="hidden" name="filas[${nuevaPos}][col7]" value="${formatoCLP(subtotal)}">
            <input type="hidden" name="filas[${nuevaPos}][col8]" value='<a href="#tablaParametros" onclick="eliminarFila(this, ${filaIndex})"><i class="fa fa-trash text-danger"></a>'>
        `;
            actualizarTotales();
            filaIndex++;
            $('#id_producto').val('');
            $('#id_producto').trigger('change');
            $('#precio').val('');
            $('#descuento').val(0);
            $('#cantidad').val(0);
        }
    }


    function eliminarFila(boton, indice) {
        // Eliminar la fila visible
        let fila = boton.parentNode.parentNode;
        fila.remove();

        // Eliminar los inputs ocultos asociados a esta fila
        let filasOcultas = document.getElementById('filasOcultas');
        let inputs = filasOcultas.querySelectorAll(`input[name^="filas[${indice}]"]`);
        inputs.forEach(input => input.remove());

        // Si es necesario, ajustar los índices de las filas restantes
        actualizarIndices();
        actualizarTotales();
    }

    function actualizarIndices() {
        let filasOcultas = document.getElementById('filasOcultas');
        let inputs = filasOcultas.querySelectorAll('input[name^="filas"]');

        // Reasignar los índices de las filas visibles
        let tabla = document.getElementById('tablaParametros').getElementsByTagName('tbody')[0];
        for (let i = 0; i < tabla.rows.length; i++) {
            let inputsDeFila = filasOcultas.querySelectorAll(`input[name^="filas[${i}]"]`);
            inputsDeFila.forEach(input => {
                let nuevoNombre = input.name.replace(/\[.*?\]/, `[${i}]`);
                input.setAttribute('name', nuevoNombre);
            });
        }
    }

    function actualizarTotales() {
        // Variables para almacenar los totales
        let totalCol2 = 0;
        let totalCol3 = 0;
        let totalCol4 = 0;
        let totalCol5 = 0;
        let totalCol6 = 0;
        let totalCol7 = 0;

        // Recorre todas las filas de la tabla y suma los valores de las columnas 2 a 7
        let filas = document.getElementById("tablaParametros").getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        for (let i = 0; i < filas.length; i++) {
            totalCol2 += parseInt(filas[i].cells[1].innerHTML.replace(/\./g, '').replace('$', '')); // Eliminamos el formato
            totalCol3 += parseInt(filas[i].cells[2].innerHTML.replace(/\./g, '').replace('$', ''));
            totalCol4 += parseInt(filas[i].cells[3].innerHTML.replace(/\./g, '').replace('$', ''));
            totalCol5 += parseInt(filas[i].cells[4].innerHTML.replace(/\./g, '').replace('$', ''));
            totalCol6 += parseInt(filas[i].cells[5].innerHTML.replace(/\./g, '').replace('$', ''));
            totalCol7 += parseInt(filas[i].cells[6].innerHTML.replace(/\./g, '').replace('$', ''));
        }
        const formatoCLP = (valor) => {
            return valor.toLocaleString('es-CL', {
                style: 'currency',
                currency: 'CLP'
            });
        };

        // Actualiza los elementos del pie de tabla con los nuevos totales
        document.getElementById("tc2").innerHTML = (totalCol2);
        document.getElementById("tc3").innerHTML = formatoCLP(totalCol3);
        document.getElementById("tc4").innerHTML = ''; //totalCol4 + '%';
        document.getElementById("tc5").innerHTML = ''; //formatoCLP(totalCol5);
        document.getElementById("tc6").innerHTML = formatoCLP(totalCol6);
        document.getElementById("tc7").innerHTML = formatoCLP(totalCol7);

    }

    function habilita_costo(id) {
        if (id == 3) {
            $('#costo_envio').prop('disabled', false);
        } else {
            $('#costo_envio').prop('disabled', true);
            $('#costo_envio').val('0');
        }
    }

    $(document).ready(function() {
        obtener_comunas($('#id_region').val());
        setTimeout
            (
                function() {
                    $('#id_comuna').val($('#hidden_comuna').val());
                    $('#id_comuna').trigger('change');
                    actualizarTotales();
                },
                1000
            );

    });
</script>
@stop