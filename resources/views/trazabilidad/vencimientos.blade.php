@extends('adminlte::page')

@section('title', 'Vencimientos')

@section('content_header')
<span class="text-xl text-bold text-dark">Vencimientos</span> <span> Lista</span>
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
            @if (session()->has('message'))
            <!-- <div class="alert alert-success" style="display: block;" id="msgsuccess">
                {{ session('message') }}
                <script>
                    setTimeout(function() {
                        document.getElementById('msgsuccess').style.display = 'none';
                    }, 2000);
                </script>
            </div> -->
            @endif

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header  bg-dark">
                        <h3 class="card-title">Suministros</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row table-responsive">
                            <div class="col-md-12">
                                <table id="tablaDispositivos" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
                                    <thead>
                                        <tr>
                                            <th class="text-center">fecha</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Dispositivo</th>
                                            <th class="text-center">Lote</th>
                                            <th class="text-center">Ubicación</th>
                                            <th class="text-center">Vencimiento</th>
                                            <th class="text-center">Guía de Despacho</th>
                                            <th class="text-center">Factura</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach($vencimientos as $v)

                                        <tr @if($v->id_estado != 1) style="background-color:#d2d2d2 !important" @endif>
                                            <td>{{$v->vencimiento}}</td>
                                            <td>{{$v->cliente}}</td>
                                            <td>{{$v->nombre}} - {{$v->marca}} - {{$v->modelo}}</td>
                                            <td>{{$v->lote}}</td>
                                            <td>{{$v->ubicacion}}</td>
                                            <td class="text-center" @if($v->id_estado == 1) style="background-color: {{$v->color}};" @endif>{{$v->fecha}}</td>
                                            <td>{{$v->guia_despacho}}</td>
                                            <td>{{$v->factura}}</td>
                                            <td>{{$v->estado}}</td>
                                            <td class="text-center" nowrap>
                                                <a href="#" onclick="modal_vencimiento({{$v->id}})"><i class="fa fa-edit text-dark editar_vencimiento" data-toggle="tooltip" data-placement="top" title="Modificar"></i></a>&nbsp;&nbsp;
                                                <a href="{{route('trazabilidad.editar',$v->slug)}}"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                                @if($v->id_estado == 1)
                                                &nbsp;&nbsp;<a href="#" onclick="cambiar_estado('/trazabilidad/cambiar_estado',{{$v->id}},2)"><i class="fa fa-times-circle text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Obsoleto"></i></a>
                                                @endif
                                                @if($v->id_estado == 2)
                                                &nbsp;&nbsp;<a href="#" onclick="cambiar_estado('/trazabilidad/cambiar_estado',{{$v->id}},1)"><i class="fa fa-check-circle text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Activo"></i></a>
                                                @endif
                                                &nbsp;&nbsp;<a href="#" onclick="eliminar('/trazabilidad/eliminar/',{{$v->id}})"><i class="fa fa-trash text-danger " data-toggle="tooltip" data-placement="top" title="Eliminar"></i></a>
                                            </td>
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
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row table-responsive">
                            <div class="col-md-12">
                                <table id="tablaMantenciones" class="table table-bordered table-hover dataTable dtr-inline table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Tipo</th>
                                            <th class="text-center">Vencimiento</th>
                                            <th class="text-center">Guía de Despacho</th>
                                            <th class="text-center">Factura</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach($mantenciones as $m)
                                        <tr @if($m->id_estado != 1) style="background-color:#d2d2d2;" @endif>
                                            <td>{{$m->vencimiento}}</td>
                                            <td>{{$m->cliente}}</td>
                                            <td>{{$m->tipo}}</td>
                                            <td class="text-center" @if($m->id_estado == 1) style="background-color: {{$m->color}};" @endif>{{$m->fecha}}</td>
                                            <td>{{$m->guia_despacho}}</td>
                                            <td>{{$m->factura}}</td>
                                            <td>{{$m->estado}}</td>
                                            <td class="text-center" nowrap>
                                                <a href="#" onclick="modal_mantencion({{$m->id}})"><i class="fa fa-edit text-dark editar_vencimiento" data-toggle="tooltip" data-placement="top" title="Modificar"></i></a>&nbsp;&nbsp;
                                                <a href="{{route('trazabilidad.editar',$m->slug)}}"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                                @if($m->id_estado == 1)
                                                &nbsp;&nbsp;<a href="#" onclick="cambiar_estado('/trazabilidad/cambiar_estado_mantencion',{{$m->id}},2)"><i class="fa fa-check-circle text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Realizada"></i></a>
                                                @endif
                                                @if($m->id_estado == 2)
                                                &nbsp;&nbsp;<a href="#" onclick="cambiar_estado('/trazabilidad/cambiar_estado_mantencion',{{$m->id}},1)"><i class="fa fa-times-circle text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Activa"></i></a>
                                                @endif
                                                &nbsp;&nbsp;<a href="#" onclick="eliminar('/trazabilidad/eliminar_mantencion/',{{$m->id}})"><i class="fa fa-trash text-danger " data-toggle="tooltip" data-placement="top" title="Eliminar"></i></a>
                                            </td>
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
<!-- editar vencimiento -->
<div class="modal fade" id="modal-vencimiento">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="fModal" action="{{route('trazabilidad.guardar_vencimiento')}}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Modificar</h4>
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
                                <input type="hidden" id="id_trazabilidad_producto" name="id_trazabilidad_producto" value="" />
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
                    <button type="button" onclick="guardar_vencimiento()" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn bg-dark" data-dismiss="modal"><i class="fa fa-ban"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- editar mantencion -->
<div class="modal fade" id="modal-editar_mp">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="fModal" action="{{route('trazabilidad.guardar_mantencion_editar')}}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Mantención Preventiva</h4>
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
                                <input type="hidden" id="id_trazabilidad_mantencion" name="id_trazabilidad_mantencion" value="" />
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
                    <button type="button" onclick="guardar_mantencion_editar()" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn bg-dark" data-dismiss="modal"><i class="fa fa-ban"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
<link rel="stylesheet" href="//cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.dataTables.css">
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
<!-- <script src="//cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="//cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.js"></script>
<script src="//cdn.datatables.net/rowgroup/1.5.0/js/rowGroup.dataTables.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#id_cliente').val($('#id_cliente_bd').val()); // Select the option with a value of '1'
        $('#id_cliente').trigger('change');
        $('#id_producto').val($('#id_producto_bd').val()); // Select the option with a value of '1'
        $('#id_producto').trigger('change');
        activaTab($('#tab_seleccionado').val());

        var groupColumn = 1;
        var table = $('#tablaDispositivos').DataTable({
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
            columnDefs: [{
                visible: false,
                targets: groupColumn
            }, {
                targets: [0],
                visible: false
            }, {
                targets: [2],
                visible: false
            }],
            order: [
                [groupColumn, 'asc'],
                [4, 'asc']
            ],
            // rowGroup: {
            //     dataSrc: [1, 2]
            // },
            // columnDefs: [{
            //     targets: [0, 1, 2, groupColumn],
            //     visible: false
            // }],
            displayLength: 10,
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                        page: 'current'
                    })
                    .data()
                    .each(function(group, i) {
                        if (last !== group) {
                            $(rows)
                                .eq(i)
                                .before(
                                    '<tr class="group" style="background-color: #811916;color:#fff;"><td colspan="9">' +
                                    group +
                                    '</i></td></tr>'
                                );

                            last = group;
                        }
                    });
            }
        });

        var table = $('#tablaMantenciones').DataTable({
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
            columnDefs: [{
                visible: false,
                targets: groupColumn
            }, {
                targets: [0],
                visible: false
            }],
            order: [
                [groupColumn, 'asc'],
                [0, 'asc']
            ],
            displayLength: 10,
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                        page: 'current'
                    })
                    .data()
                    .each(function(group, i) {
                        if (last !== group) {
                            $(rows)
                                .eq(i)
                                .before(
                                    '<tr class="group" style="background-color: #811916;color:#fff;"><td colspan="6">' +
                                    group +
                                    '</i></td></tr>'
                                );

                            last = group;
                        }
                    });
            }
        });
    });

    $('#modal-agregar').on('hidden.bs.modal', function() {
        location.reload();
    })
    $('#modal-vencimiento').on('hidden.bs.modal', function() {
        location.reload();
    })
    $('#modal-editar_mp').on('hidden.bs.modal', function() {
        location.reload();
    })

    $('[data-toggle="tooltip"]').tooltip();
</script>
@stop