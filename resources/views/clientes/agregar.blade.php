@extends('adminlte::page')

@section('title', 'Clientes - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Clientes</span> <span> Agregar</span>
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
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Datos Principales</h3>
                    </div>
                    <form method="post" id="fClientes" action="{{route('clientes.crear')}}">
                        @csrf
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
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón social" value="{{old('razon_social')}}" required>
                                        <span id="error_rz" class="error">Debe ingresar una razon social</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Rubro</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_rubro" name="id_rubro" required style="width:100%">
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
                                        <input class="form-control" type="text" id="giro" name="giro" value="{{old('giro')}}" />
                                        <span id="error_giro" class="error">Debe ingresar un giro</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="region">Región</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_region" name="id_region" onchange="obtener_comunas(this.value)" style="width:100%" required>
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
                                        <select class="form-control select2" id="id_comuna" name="id_comuna" style="width:100%" required>
                                            <option value="">Seleccione</option>
                                        </select>
                                        <span id="error_comuna" class="error">Debe seleccionar una comuna</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" value="{{old('direccion')}}" required>
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
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="888888888" value="{{old('telefono')}}" />
                                        <span id="error_telefono" class="error">Teléfono no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Correo electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" value="{{old('correo')}}" />
                                        <span id="error_correo" class="error">Correo electrónico no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sw">Sitio Web</label>
                                        <input type="text" class="form-control" id="sitio_web" name="sitio_web" placeholder="Sitio Web" value="{{old('sitio_web')}}" />
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
                                            <input type="text" class="form-control" id="nombre_principal" name="nombre_principal" placeholder="Nombre" value="{{old('nombre_principal')}}" />
                                            <span id="error_nombre_ppal" class="error">Teléfono no válido</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono_principal" name="telefono_principal" placeholder="888888888" value="{{old('telefono_principal')}}" />
                                            <span id="error_telefono_ppal" class="error">Teléfono no válido</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico</label>
                                            <input type="email" class="form-control" id="correo_principal" name="correo_principal" value="{{old('correo_principal')}}" placeholder="Correo electrónico" />
                                            <span id="error_correo_ppal" class="error">Correo electrónico no válido</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Contacto(s) Secundario(s)</h3>
                            </div>
                            <div class="card-body">
                                Debe guardar el registro antes de agregar contactos secundarios
                            </div>
                        </div>
                        <div>
                            <button type="button" onclick="validar_cliente()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                            <a href="{{route('clientes.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
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
    </script>
    @stop