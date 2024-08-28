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
            <div class="alert alert-success" style="display: block;" id="msgsuccess">
                {{ session('message') }}
                <script>
                    setTimeout(function() {
                        document.getElementById('msgsuccess').style.display = 'none';
                    }, 2000);
                </script>
            </div>
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
                        <div class="row">
                            <div class="col-md-12">
                                <table id="tablaDispositivos" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Dispositivo</th>
                                            <th class="text-center">Lote</th>
                                            <th class="text-center">Ubicación</th>
                                            <th class="text-center">Vencimiento</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach($vencimientos as $v)
                                        <tr @if($v->id_estado != 1) style="background-color:#d2d2d2 !important" @endif>
                                            <td>{{$v->cliente}}</td>
                                            <td>{{$v->nombre}} - {{$v->marca}} - {{$v->modelo}}</td>
                                            <td>{{$v->lote}}</td>
                                            <td>{{$v->ubicacion}}</td>
                                            <td class="text-center" @if($v->id_estado == 1) style="background-color: {{$v->color}};" @endif>{{$v->fecha}}</td>
                                            <td>{{$v->estado}}</td>
                                            <td class="text-center">
                                                <a href="{{route('trazabilidad.editar',$v->slug)}}"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                                @if($v->id_estado == 1)
                                                &nbsp;&nbsp;<a href="#" onclick="cambiar_estado('/trazabilidad/cambiar_estado',{{$v->id}})"><i class="fa fa-times-circle text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Obsoleto"></i></a>
                                                @endif
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
                        <div class="row">
                            <div class="col-md-12">
                                <table id="tablaMantenciones" class="table table-bordered table-hover dataTable dtr-inline table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Tipo</th>
                                            <th class="text-center">Vencimiento</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach($mantenciones as $m)
                                        <tr @if($m->id_estado != 1) style="background-color:#d2d2d2;" @endif>
                                            <td>{{$m->cliente}}</td>
                                            <td>{{$m->tipo}}</td>
                                            <td class="text-center" @if($m->id_estado == 1) style="background-color: {{$m->color}};" @endif>{{$m->fecha}}</td>
                                            <td>{{$m->estado}}</td>
                                            <td class="text-center">
                                                <a href="{{route('trazabilidad.editar',$m->slug)}}"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                                @if($m->id_estado == 1)
                                                &nbsp;&nbsp;<a href="#" onclick="cambiar_estado('/trazabilidad/cambiar_estado_mantencion',{{$m->id}})"><i class="fa fa-check-circle text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Realizada"></i></a>
                                                @endif
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
        activaTab($('#tab_seleccionado').val());

        var groupColumn = 0;
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
            }],
            order: [
                [groupColumn, 'asc']
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
            }],
            order: [
                [groupColumn, 'asc']
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
                                    '<tr class="group" style="background-color: #811916;color:#fff;"><td colspan="5">' +
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

    $('[data-toggle="tooltip"]').tooltip();
</script>
@stop