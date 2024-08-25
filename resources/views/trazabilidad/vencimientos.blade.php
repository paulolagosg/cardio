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
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card card-dark card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Suministros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Mantenciones Preventivas</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                <div class="col-md-12 table-responsive">
                                    <table id="tablaDispositivos" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Cliente</th>
                                                <th class="text-center">Dispositivo</th>
                                                <th class="text-center">Lote</th>
                                                <th class="text-center">Ubicación</th>
                                                <th class="text-center">Vencimiento</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach($vencimientos as $v)
                                            <tr>
                                                <td>{{$v->cliente}}</td>
                                                <td>{{$v->nombre}} - {{$v->marca}} - {{$v->modelo}}</td>
                                                <td>{{$v->lote}}</td>
                                                <td>{{$v->ubicacion}}</td>
                                                <td class="text-center" style="background-color: {{$v->color}};">{{$v->fecha}}</td>
                                                <td class="text-center"><a href="{{route('trazabilidad.editar',$v->slug)}}"><i class="fa fa-eye text-dark"></i></a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <div class="col-md-12 table-responsive">
                                    <table id="tablaMantenciones" class="table table-bordered table-hover dataTable dtr-inline table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Cliente</th>
                                                <th class="text-center">Tipo</th>
                                                <th class="text-center">Vencimiento</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach($mantenciones as $m)
                                            <tr>
                                                <td>{{$m->cliente}}</td>
                                                <td>otro</td>
                                                <td class="text-center" style="background-color: {{$m->color}};">{{$m->fecha}}</td>
                                                <td class="text-center"><a href="{{route('trazabilidad.editar',$m->slug)}}"><i class="fa fa-eye text-dark"></i></a></td>
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
                                    '<tr class="group" style="background-color: #811916;color:#fff;"><td colspan="5">' +
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
</script>
@stop