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
                <form method="post" id="fClientes" action="{{route('trazabilidad.crear_sc')}}">
                    @csrf
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-title p-2">
                                <h4 class="text-bold">Datos del Cliente</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">RUT</label> <label class="obligatorio">*</label>
                                            <input type="text" class="form-control" id="rut" name="rut" placeholder="99999999-9" value="{{old('rut')}}" required>
                                            <span id="error_rut" class="error">Debe ingresar un rut</span>
                                            <span id="error_rut_val" class="error">RUT no válido</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label> <label class="obligatorio">*</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{old('nombre')}}" required>
                                            <span id="error_nombre" class="error">Debe ingresar un nombre</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Razón Social</label> <label class="obligatorio">*</label>
                                            <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón social" value="{{old('nombre')}}" required>
                                            <span id="error_rz" class="error">Debe ingresar una razon social</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre">Rubro</label> <label class="obligatorio">*</label>
                                            <select class="form-control select2" id="id_rubro" name="id_rubro" required>
                                                <option value="">Seleccione</option>
                                                @foreach($rubros as $rubro)
                                                <option value="{{$rubro->id}}">{{$rubro->nombre}}</option>
                                                @endforeach
                                            </select>
                                            <span id="error_rubro" class="error">Debe seleccionar un rubro</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre">Giro</label> <label class="obligatorio">*</label>
                                            <input class="form-control" type="text" id="giro" name="giro" value="" />
                                            <span id="error_giro" class="error">Debe ingresar un giro</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="region">Región</label> <label class="obligatorio">*</label>
                                            <select class="form-control select2" id="id_region" name="id_region"
                                                onchange="obtener_comunas(this.value)" required>
                                                <option value="">Seleccione</option>
                                                @foreach($regiones as $region)
                                                <option value="{{$region->id}}">{{$region->nombre}}</option>
                                                @endforeach
                                            </select>
                                            <span id="error_region" class="error">Debe seleccionar una región</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="comuna">Comuna</label> <label class="obligatorio">*</label>
                                            <select class="form-control select2" id="id_comuna" name="id_comuna"
                                                required>
                                                <option value="">Seleccione</option>
                                            </select>
                                            <span id="error_comuna" class="error">Debe seleccionar una comuna</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="direccion">Dirección</label> <label class="obligatorio">*</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
                                            <span id="error_direccion" class="error">Debe ingresar una dirección</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telefono">Latitud (GoogleMaps)</label>
                                            <input type="text" class="form-control" id="latitud" name="latitud" value="{{old('latitud')}}" placeholder="-99.999" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Longitud (GoogleMaps)</label>
                                            <input type="email" class="form-control" id="longitud" name="longitud" value="{{old('longitud')}}" placeholder="99.999" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="888888888" />
                                            <span id="error_telefono" class="error">Teléfono no válido</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo"
                                                placeholder="Correo electrónico" />
                                            <span id="error_correo" class="error">Correo electrónico no válido</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sw">Sitio Web</label>
                                            <input type="text" class="form-control" id="sitio_web" name="sitio_web" placeholder="Sitio Web" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-title p-2">
                                <h4 class="text-bold">Datos del Equipo</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">DEA</label> <label class="obligatorio">*</label>
                                            <select class="form-control select2" id="id_producto" name="id_producto" required>
                                                <option value="">Seleccione</option>
                                                @foreach($deas as $d)
                                                <option value="{{$d->id}}">{{$d->nombre}}</option>
                                                @endforeach
                                            </select>
                                            <span id="error_dea" class="error">Debe seleccionar un equipo</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Ubicación Exacta del Equipo</label> <label class="obligatorio">*</label>
                                            <input class="form-control" type="text" id="ubicacion" name="ubicacion" value="" />
                                            <span id="error_ubicacion" class="error">Debe ingresar una ubicación</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Número de Serie</label> <label class="obligatorio">*</label>
                                            <input class="form-control" type="text" id="numero_serie" name="numero_serie" value="" />
                                            <span id="error_serie" class="error">Debe ingresar un numero de serie</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Factura Asociada</label>
                                            <input class="form-control" type="text" id="factura" name="factura" value="" />
                                            <span id="error_factura" class="error">Debe ingresar un numero de factura</span>
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
                    </div>
                    <div class="col-md-12">
                        <div>
                            <button type="button" onclick="trazabilidad_cliente()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                            <a href="{{route('trazabilidad.vencimientos')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                        </div>
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
    $(function() {
        $("#rut").rut().on('rutValido', function(e, rut, dv) {
            alert("El rut " + rut + "-" + dv + " es correcto");
        }, {
            minimumLength: 7
        });
    })
</script>
@stop