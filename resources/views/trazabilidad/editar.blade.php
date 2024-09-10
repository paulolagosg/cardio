@extends('adminlte::page')

@section('title', 'Trazabilidad - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Trazabilidad</span> <span> Modificar</span>
@stop
@section('content')

<div class="card  card-dark">
    <div class="card-body">
        <div class="row">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if (session('error'))
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
                <form method="post" id="fClientes" action="{{route('trazabilidad.actualizar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Cliente</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_cliente" name="id_cliente" required disabled>
                                            <option value="">Seleccione</option>
                                            @foreach($clientes as $c)
                                            <option value="{{$c->id}}">{{$c->id}} - {{$c->nombre}} ({{$c->razon_social}})</option>
                                            @endforeach
                                        </select>
                                        <span id="error_rubro" class="error">Debe seleccionar un cliente</span>
                                        <input type="hidden" id="id" name="id" value="{{$datos->id}}" />
                                        <input type="hidden" id="id_cliente_bd" name="id_cliente_bd" value="{{$datos->id_cliente}}" />
                                        <input type="hidden" id="id_producto_bd" name="id_producto_bd" value="{{$datos->id_producto}}" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">DEA</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_producto" name="id_producto" required disabled>
                                            <option value="">Seleccione</option>
                                            @foreach($deas as $d)
                                            <option value="{{$d->id}}">{{$d->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_rubro" class="error">Debe seleccionar un cliente</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Ubicación Exacta del Equipo</label> <label class="obligatorio">*</label>
                                        <input class="form-control" type="text" id="ubicacion" name="ubicacion" value="{{$datos->ubicacion}}" />
                                        <span id="error_giro" class="error">Debe ingresar una ubicación</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Número de Serie</label> <label class="obligatorio">*</label>
                                        <input class="form-control" type="text" id="numero_serie" name="numero_serie" value="{{$datos->numero_serie}}" disabled />
                                        <span id="error_giro" class="error">Debe ingresar un numero de serie</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p>
                                        <a href="#" onclick="trazabilidad_con_cliente()" class="btn btn-success"><i class="fa fa-save"></i> Guardar</a>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header  bg-dark">
                                            <h3 class="card-title">Suminstros</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <button type="button" onclick="agregar_producto_vencimiento()" class="btn text-white bg-dark"><i class="fa fa-plus-circle"></i> Agregar Suministro</a>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 table-responsive">
                                                    <table id="tablaDispositivos" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Fecha</th>
                                                                <th class="text-center">Nombre</th>
                                                                <th class="text-center">Lote</th>
                                                                <th class="text-center">Vencimiento</th>
                                                                <th class="text-center">Guía Despacho</th>
                                                                <th class="text-center">Factura</th>
                                                                <th class="text-center">Estado</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Fecha</th>
                                                                <th>Nombre</th>
                                                                <th>Lote</th>
                                                                <th>Vencimiento</th>
                                                                <th>Guía Despacho</th>
                                                                <th>Factura</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($dispositivos as $dispositivo)
                                                            <tr @if($dispositivo->id_estado != 1) style="background-color:#d2d2d2 !important;" @endif >
                                                                <td>{{$dispositivo->vencimiento}}</td>
                                                                <td>{{$dispositivo->nombre}} - {{$dispositivo->marca}} - {{$dispositivo->modelo}}</td>
                                                                <td>{{$dispositivo->lote}}</td>
                                                                <td class="text-center" @if($dispositivo->id_estado == 1)style="background-color:{{$dispositivo->color}}" @endif>{{$dispositivo->fecha}}</td>
                                                                <td>{{$dispositivo->guia_despacho}}</td>
                                                                <td>{{$dispositivo->factura}}</td>
                                                                <td>{{$dispositivo->estado}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header  bg-dark">
                                            <h3 class="card-title">Mantenciones Preventivas</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <button type="button" onclick="agregar_mantencion()" class="btn text-white bg-dark"><i class="fa fa-plus-circle"></i> Agregar Mantención</a>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 table-responsive">
                                                    <table id="tablaMantenciones" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">fecha</th>
                                                                <th class="text-center">Tipo</th>
                                                                <th class="text-center">Vencimiento</th>
                                                                <th class="text-center">Guía Despacho</th>
                                                                <th class="text-center">Factura</th>
                                                                <th class="text-center">Estado</th>
                                                            </tr>
                                                            <tr>
                                                                <th>fecha</th>
                                                                <th>Tipo</th>
                                                                <th>Vencimiento</th>
                                                                <th>Guía Despacho</th>
                                                                <th>Factura</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($mantenciones as $m)
                                                            <tr @if($m->id_estado != 1) style="background-color:#d2d2d2 !important;" @endif>
                                                                <td>{{$m->vencimiento}}</td>
                                                                <td>{{$m->tipo}}</td>
                                                                <td class="text-center" @if($m->id_estado == 1) style="background-color:{{$m->color}}" @endif>{{$m->fecha}}</td>
                                                                <td>{{$m->guia_despacho}}</td>
                                                                <td>{{$m->factura}}</td>
                                                                <td class="text-center">{{$m->estado}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button> -->
                        <a href="{{route('trazabilidad.vencimientos')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-arrow-left"></i> Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- agregar dispositivo -->
<div class="modal fade" id="modal-agregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="fModal" action="{{route('trazabilidad.guardar_dispositivo')}}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Dispositivo</h4>
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
                                <label for="nombre">Tipo</label>
                                <select class="form-control select2" id="id_tipo_producto" name="id_tipo_producto" style="width:100%" required onchange="obtener_productos(this.value)">
                                    <option value="">Seleccione</option>
                                    @foreach($tipos_productos as $tipo)
                                    <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                    @endforeach
                                </select>
                                <span id="error_tproducto_modal" class="error">Debe seleccionar un tipo</span>
                                <input type="hidden" id="id_trazabilidad" name="id_trazabilidad" value="{{$datos->id}}" />
                                <input type="hidden" name="id_token" id="id_token" value="{{ csrf_token() }}" />
                            </div>
                            <div class="form-group">
                                <label for="nombre">Suministro</label>
                                <select class="form-control select2" id="id_producto_d" name="id_producto_d" style="width:100%" required>
                                    <option value="">Seleccione</option>
                                </select>
                                <span id="error_suministro_modal" class="error">Debe seleccionar un suministro</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Lote</label>
                                <input type="text" class="form-control" id="lote" name="lote" value="" required>
                                <span id="error_lote_modal" class="error">Debe ingresar un lote</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Vencimiento</label>
                                <input type="date" class="form-control  datepicker" id="vencimiento" name="vencimiento" value="" required>
                                <span id="error_vencimiento_modal" class="error">Debe ingresar una fecha</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Guía de despacho asociada</label>
                                <input type="text" class="form-control" id="guia" name="guia" value="" required>
                                <span id="error_guia" class="error">Debe ingresar una fecha</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Factura asociada</label>
                                <input type="text" class="form-control" id="factura" name="factura" value="" required>
                                <span id="error_factura" class="error">Debe ingresar una fecha</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-right">
                    <button type="button" onclick="agregar_dispositivo()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                    <button type="button" class="btn bg-dark" data-dismiss="modal"><i class="fa fa-ban"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- agregar mantencion -->
<div class="modal fade" id="modal-agregar_mp">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="fModal" action="{{route('trazabilidad.guardar_mantencion')}}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Mantención Preventiva</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bodyModal">

                    <div class="row">
                        <div id="msgErrorM" class="alert alert-danger" style="display: none;">
                        </div>
                        <div id="msgOKM" class="alert alert-success" style="display: none;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nombre">Tipo</label>
                                <select class="form-control select2" id="id_tipo_mantencion" name="id_tipo_mantencion" style="width:100%" required>
                                    <option value="">Seleccione</option>
                                    @foreach($tipos_mantenciones as $tipo)
                                    <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                    @endforeach
                                </select>
                                <span id="error_tipo_mantencion_modal" class="error">Debe seleccionar un tipo</span>
                                <input type="hidden" id="id_trazabilidad" name="id_trazabilidad" value="{{$datos->id}}" />
                                <input type="hidden" name="id_token_mp" id="id_token_mp" value="{{ csrf_token() }}" />
                            </div>
                            <div class="form-group">
                                <label for="nombre">Fecha</label>
                                <input type="date" class="form-control  datepicker" id="fecha_mantencion" name="fecha_mantencion" value="" required>
                                <span id="error_fmantencion_modal" class="error">Debe ingresar una fecha</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Guía de despacho asociada</label>
                                <input type="text" class="form-control" id="guia_m" name="guia_m" value="" required>
                                <span id="error_guia_modal" class="error">Debe ingresar una fecha</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Factura asociada</label>
                                <input type="text" class="form-control" id="factura_m" name="factura_m" value="" required>
                                <span id="error_guia_modal" class="error">Debe ingresar una fecha</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-right">
                    <button type="button" onclick="guardar_mantencion()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                    <button type="button" class="btn bg-dark" data-dismiss="modal"><i class="fa fa-ban"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
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

    .page-item.active .page-link {
        background-color: #811916 !important;
        border: 1px solid #dee2e6;
        color: #fff !important;
    }

    .page-link {
        color: #811916 !important;
    }
</style>
@stop

@section('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#id_cliente').val($('#id_cliente_bd').val()); // Select the option with a value of '1'
        $('#id_cliente').trigger('change');
        $('#id_producto').val($('#id_producto_bd').val()); // Select the option with a value of '1'
        $('#id_producto').trigger('change');
        $('.alert-success').fadeIn().delay(3000).fadeOut();
        $('.alert-danger').fadeIn().delay(3000).fadeOut();

        $('#tablaDispositivos').DataTable({
            language: {
                info: 'Mostrando página _PAGE_ de _PAGES_',
                infoEmpty: 'No hay datos disponbles',
                infoFiltered: '(filtrados de _MAX_ registros totales)',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                info: "Mostrando _START_ a _END_ de _TOTAL_ ",
                zeroRecords: 'No se encontraron datos',
                emptyTable: 'No se encontraron datos',
                search: 'Buscar: ',
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            order: [
                [0, 'asc']
            ],
            columnDefs: [{
                targets: [0],
                visible: false
            }],
            initComplete: function() {
                this.api().columns([0, 1, 2, 3, 4, 5]).every(function() {
                    var that = this;
                    var input = $('<input size="10" type="text" placeholder="Buscar.." />')
                        .appendTo($(this.header()).empty())
                        .on('keyup change', function() {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                });

            }
        });
        $('#tablaMantenciones').DataTable({
            language: {
                info: 'Mostrando página _PAGE_ de _PAGES_',
                infoEmpty: 'No hay datos disponbles',
                infoFiltered: '(filtrados de _MAX_ registros totales)',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                info: "Mostrando _START_ a _END_ de _TOTAL_ ",
                zeroRecords: 'No se encontraron datos',
                emptyTable: 'No se encontraron datos',
                search: 'Buscar: ',
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            order: [
                [0, 'asc']
            ],
            columnDefs: [{
                targets: [0],
                visible: false
            }],
            initComplete: function() {
                this.api().columns([0, 1, 2, 3, 4]).every(function() {
                    var that = this;
                    var input = $('<input size="10" type="text" placeholder="Buscar.." />')
                        .appendTo($(this.header()).empty())
                        .on('keyup change', function() {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                });

            }
        });
    });

    $('#modal-agregar').on('hidden.bs.modal', function() {
        location.reload();
    });
    $('#modal-agregar_mp').on('hidden.bs.modal', function() {
        location.reload();
    });
</script>
@stop