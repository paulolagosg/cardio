@extends('adminlte::page')

@section('title', 'Clientes - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Clientes</span> <span> Modificar</span>
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

                <div class="card">

                    <form method="post" id="fClientes" action="{{route('clientes.actualizar')}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">RUT</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="rut" name="rut" placeholder="99999999-9" value="{{old('rut',$datos->rut)}}" required disabled>
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
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{old('nombre',$datos->nombre)}}" required>
                                        <span id="error_nombre" class="error">Debe ingresar un nombre</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Razón Social</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón social" value="{{old('razon_social',$datos->razon_social)}}" required>
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
                                            <option value="{{$rubro->id}}" @if($rubro->id == $datos->id_rubro) selected @endif >{{$rubro->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_rubro" class="error">Debe seleccionar un rubro</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Giro</label> <label class="obligatorio">*</label>
                                        <input class="form-control" type="text" id="giro" name="giro" value="{{$datos->giro}}" />
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
                                            <option value="{{$region->id}}" @if($region->id == $datos->id_region) selected @endif >{{$region->nombre}}</option>
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
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required value="{{$datos->direccion}}">
                                        <span id="error_direccion" class="error">Debe ingresar una dirección</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="888888888" value="{{old('telefono',$datos->telefono)}}" />
                                        <span id="error_telefono" class="error">Teléfono no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Correo electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo"
                                            placeholder="Correo electrónico" value="{{old('correo',$datos->correo)}}" />
                                        <span id="error_correo" class="error">Correo electrónico no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sw">Sitio Web</label>
                                        <input type="text" class="form-control" id="sitio_web" name="sitio_web" placeholder="Sitio Web" value="{{old('sitio_web',$datos->sitio_web)}}" />
                                    </div>
                                </div>
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
<script>
    $(document).ready(function() {
        obtener_comunas($('#id_region_bd').val());
        setTimeout(function() {
            $('#id_comuna').val($('#id_comuna_bd').val()); // Select the option with a value of '1'
            $('#id_comuna').trigger('change');
        }, 2000);
    });
</script>
@stop