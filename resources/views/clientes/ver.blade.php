@extends('adminlte::page')

@section('title', 'Clientes - Ver')

@section('content_header')
<span class="text-xl text-bold text-dark">Clientes</span> <span> Ver</span>
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
                <form method="post" id="fClientes" action="{{route('clientes.actualizar')}}">
                    @csrf
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Datos Principales</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">RUT</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="rut" name="rut" placeholder="99999999-9" value="{{old('rut',$datos->rut)}}" required onblur="validarGuionRut(this)" disabled>
                                        <span id="error_rut" class="error">Debe ingresar un rut</span>
                                        <span id="error_rut_val" class="error">RUT no válido</span>
                                        <input type="hidden" name="id" id="id" value="{{$datos->id}}" />
                                        <input type="hidden" name="id_rubro_bd" id="id_rubro_bd" value="{{$datos->id_rubro}}" />
                                        <input type="hidden" name="id_region_bd" id="id_region_bd" value="{{$datos->id_region}}" />
                                        <input type="hidden" name="id_comuna_bd" id="id_comuna_bd" value="{{$datos->id_comuna}}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{old('nombre',$datos->nombre)}}" disabled required>
                                        <span id="error_nombre" class="error">Debe ingresar un nombre</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Razón Social</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón social" value="{{old('razon_social',$datos->razon_social)}}" disabled required>
                                        <span id="error_rz" class="error">Debe ingresar una razon social</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Rubro</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_rubro" name="id_rubro" disabled required>
                                            <option value="">Seleccione</option>
                                            @foreach($rubros as $rubro)
                                            <option value="{{$rubro->id}}" @if($rubro->id == $datos->id_rubro) selected @endif >{{$rubro->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_rubro" class="error">Debe seleccionar un rubro</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Giro</label> <label class="obligatorio">*</label>
                                        <input class="form-control" type="text" id="giro" name="giro" value="{{$datos->giro}}" disabled />
                                        <span id="error_giro" class="error">Debe ingresar un giro</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="region">Región</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_region" name="id_region"
                                            onchange="obtener_comunas(this.value)" required disabled>
                                            <option value="">Seleccione</option>
                                            @foreach($regiones as $region)
                                            <option value="{{$region->id}}" @if($region->id == $datos->id_region) selected @endif >{{$region->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_region" class="error">Debe seleccionar una región</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="comuna">Comuna</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_comuna" name="id_comuna" disabled
                                            required>
                                            <option value="">Seleccione</option>
                                        </select>
                                        <span id="error_comuna" class="error">Debe seleccionar una comuna</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" disabled required value="{{$datos->direccion}}">
                                        <span id="error_direccion" class="error">Debe ingresar una dirección</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Latitud (GoogleMaps)</label>
                                        <input type="text" class="form-control" id="latitud" name="latitud" disabled value="{{$datos->latitud}}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Longitud (GoogleMaps)</label>
                                        <input type="email" class="form-control" id="longitud" name="longitud" disabled value="{{$datos->longitud}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="888888888" disabled value="{{old('telefono',$datos->telefono)}}" />
                                        <span id="error_telefono" class="error">Teléfono no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Correo electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" disabled
                                            placeholder="Correo electrónico" value="{{old('correo',$datos->correo)}}" />
                                        <span id="error_correo" class="error">Correo electrónico no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sw">Sitio Web</label>
                                        <input type="text" class="form-control" id="sitio_web" name="sitio_web" disabled placeholder="Sitio Web" value="{{old('sitio_web',$datos->sitio_web)}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Contacto Principal</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sw">Nombre</label>
                                            <input type="text" class="form-control" id="nombre_principal" name="nombre_principal" value="{{$contacto_principal->nombre}}" disabled placeholder="Nombre" />
                                            <input type="hidden" name="id_cp" id="id_cp" value="{{$contacto_principal->id}}" />
                                            <span id="error_nombre_ppal" class="error">Teléfono no válido</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono_principal" name="telefono_principal" value="{{$contacto_principal->telefono}}" disabled placeholder="888888888" />
                                            <span id="error_telefono_ppal" class="error">Teléfono no válido</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico</label>
                                            <input type="email" class="form-control" id="correo_principal" name="correo_principal" disabled placeholder="Correo electrónico" value="{{$contacto_principal->correo_electronico}}" />
                                            <span id="error_correo_ppal" class="error">Correo electrónico no válido</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Contacto(s) Secundario(s)</h3>
                                <div class="card-tools">

                                </div>
                            </div>

                            <div class="card-body">
                                <p><button type="button" onclick="modal_secundario()" class="btn text-white bg-dark"><i class="fa fa-plus-circle"></i> Agregar Contacto Secundario
                                    </button></p>
                                <table id="tablaContactos" class="table table-bordered table-hover dataTable dtr-inline table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Teléfono</th>
                                            <th class="text-center">Correo electrónico</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Teléfono</th>
                                            <th>Correo electrónico</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_secundarios">
                                        @foreach($contactos_secundarios as $contacto)
                                        <tr>
                                            <td>{{$contacto->nombre}}</td>
                                            <td>{{$contacto->telefono}}</td>
                                            <td>{{$contacto->correo_electronico}}</td>
                                            <td class="text-center">
                                                <!-- <a href="#" datos="{{$contacto}}" id="editar_sec_{{$contacto->id}}" data-id="{{$contacto->id}}"><i class="editar_secundario fa fa-edit text-dark" data-toggle="tooltip" data-placement="top" title="Modificar"></i></a>&nbsp;
                                                <a href="{{route('clientes.eliminar_contacto',$contacto->id)}}"><i class=" fa fa-trash text-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"></i></a>&nbsp; -->
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="button" onclick="validar_cliente()" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                        <a href="{{route('clientes.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- agregar secundario -->
<div class="modal fade" id="modal-agregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="fModal" action="{{route('clientes.guardar_secundario')}}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Contacto Secundario</h4>
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
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre_secundario" name="nombre_secundario" placeholder="Nombre" />
                                <span id="error_nombre_modal" class="error">Debe ingresar un nombre</span>
                                <input type="hidden" id="id_cliente" name="id_cliente" value="{{$datos->id}}" />
                                <input type="hidden" id="id_contacto" name="id_contacto" value="" />
                                <input type="hidden" name="id_token_mp" id="id_token_mp" value="{{ csrf_token() }}" />
                            </div>
                            <div class="form-group">
                                <label for="nombre">Teléfono</label>
                                <input type="text" class="form-control" id="telefono_secundario" name="telefono_secundario" placeholder="999999999" />
                                <span id="error_telefono_modal" class="error">Debe ingresar un teléfono válido</span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Correo Electrónico</label>
                                <input type="text" class="form-control" id="correo_secundario" name="correo_secundario" placeholder="999999999" />
                                <span id="error_correo_modal" class="error">Debe ingresar un correo válido</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-right">
                    <button type="button" onclick="agregar_secundario()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
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

    .page-item.active .page-link {
        background-color: #811916 !important;
        border: 1px solid #dee2e6;
        color: #fff !important;
    }

    .page-link {
        color: #811916 !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2();
        obtener_comunas($('#id_region_bd').val());
        setTimeout(function() {
            $('#id_comuna').val($('#id_comuna_bd').val()); // Select the option with a value of '1'
            $('#id_comuna').trigger('change');
        }, 2000);

        $('#tablaContactos').DataTable({
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
                this.api().columns([0, 1, 2]).every(function() {
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
                this.api().columns(3).every(function() {
                    var that = this;
                    var input = $('')
                        .appendTo($(this.header()).empty())
                });

            }
        });
    });
    $('#modal-agregar').on('hidden.bs.modal', function() {
        location.reload();
    });
    $('[data-toggle="tooltip"]').tooltip();
</script>
@stop