@extends('adminlte::page')

@section('title', 'Trazabilidad - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Trazabilidad</span> <span> Agregar</span>
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
            <div class="col-md-12">
                <form method="post" id="fClientes" action="{{route('trazabilidad.crear')}}">
                    @csrf
                    <div class="card">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Cliente</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_cliente" name="id_cliente" required>
                                            <option value="">Seleccione</option>
                                            @foreach($clientes as $c)
                                            <option value="{{$c->id}}">{{$c->id}} - {{$c->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_rubro" class="error">Debe seleccionar un cliente</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">DEA</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_producto" name="id_producto" required>
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
                                        <input class="form-control" type="text" id="ubicacion" name="ubicacion" value="" />
                                        <span id="error_giro" class="error">Debe ingresar una ubicación</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Número de Serie</label> <label class="obligatorio">*</label>
                                        <input class="form-control" type="text" id="numero_serie" name="numero_serie" value="" />
                                        <span id="error_giro" class="error">Debe ingresar un numero de serie</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header  bg-dark">
                                            <h3 class="card-title">Suministros</h3>
                                            <div class="card-tools">
                                                <!-- Collapse Button -->
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            Debe guardar el registro antes de agregar suministros
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
                                                <!-- Collapse Button -->
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            Debe guardar el registro antes de agregar mantenciones
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                        <a href="{{route('trazabilidad.vencimientos')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                    </div>
                </form>

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
<script>
    $(document).ready(function() {
        $('.select2').select2();


        $('.alert-success').fadeIn().delay(3000).fadeOut();
        $('.alert-danger').fadeIn().delay(3000).fadeOut();

        genera_grilla('tablaDispositivos', 'grilla_dispositivos', -1);

        $('#tablaParametros').DataTable({
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
            initComplete: function() {
                this.api().columns([0, 1, 2, 3]).every(function() {
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


    var grilla_dispositivos;

    async function genera_grilla(tGrilla, tVar, nTrazabilidad) {

        if ($.fn.DataTable.isDataTable('#' + tGrilla)) {
            $('#' + tGrilla).DataTable().destroy();
        }
        $('#' + tGrilla + ' tbody').empty();

        tVar = $('#' + tGrilla).DataTable({
            processing: true,
            serverSide: true,
            ajax: '/dispositivos/' + nTrazabilidad,
            type: 'json',
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'Todos']
            ],
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
                targets: [0, 3],
                className: 'text-right'
            }, {
                targets: [4],
                className: 'text-center'
            }, {
                targets: 3,
                render: $.fn.dataTable.render.number('.', ',', 0, '$')
            }],
            columns: [{
                    data: 'codigo',
                },
                {
                    data: 'cuenta',
                    render: function(data, type, row) {
                        return '<div class="btn-group"><button type="button" class="btn btn-default">' + row.cuenta + '</button><button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button><div class="dropdown-menu" role="menu">' + row.padre + '</div></div>';
                        //return '<div id="accordion"><div class="card" style="background-color:transparent"><div class="card-header"><h4 class="card-title w-100"><a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapse' + row.id + '" aria-expanded="false">' + data + '</a></h4></div><div id="collapse' + row.id + '" class="collapse" data-parent="#accordion" style=""><div class="card-body">' + row.padre + '</div></div></div></div></div>';
                    }


                },
                {
                    data: 'detalle',
                },
                {
                    data: 'monto_inicial',
                    // render: function(data) {
                    //     return data.render.number('.', ',', 0, '');
                    // }
                },

                {
                    data: 'id',
                    name: 'acciones',
                    render: function(data) {
                        if (nLectura == 1) {
                            return '';
                        } else {
                            return '<a href="#" onclick="editar_cuenta(' + data + ')" ><i class="fa fa-edit text-lightblue"  data-toggle="tooltip" data-placement="top" title="Editar cuenta"></i></a>&nbsp;<a href="#" onclick="eliminar_cuenta(' + data + ');" ><i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="top" title="Eliminar cuenta"></i></a>';
                        }

                    }
                }

            ],
            initComplete: function() {
                this.api().columns([0, 1, 2, 3]).every(function() {
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
    }
</script>
@stop