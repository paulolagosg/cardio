@extends('adminlte::page')

@section('title', 'Empresas - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Empresas</span> <span> Modificar</span>
@stop

@section('content')
<div class="card  card-dark">
    <div class="card-body">
        <div class="row">
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
                <form method="post" id="fEmpresas" action="{{route('empresas.actualizar')}}" enctype="multipart/form-data">
                    <div class="card card-dark">
                        <div class="card-header">
                        </div>
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">RUT</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="rut" name="rut" placeholder="99999999-9" value="{{old('rut',$datos->rut)}}" required>
                                        <input type="hidden" name="id" id="id" value="{{$datos->id}}" />
                                        <span id="error_rut" class="error">Debe ingresar un rut</span>
                                        <span id="error_rut_val" class="error">RUT no válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Razón Social</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón social" value="{{old('razon_social',$datos->razon_social)}}" required>
                                        <span id="error_rz" class="error">Debe ingresar una razon social</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Giro</label> <label class="obligatorio">*</label>
                                        <input class="form-control" type="text" id="giro" name="giro" value="{{old('giro',$datos->giro)}}" placeholder="Giro" />
                                        <span id="error_giro" class="error">Debe ingresar un giro</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" value="{{old('direccion',$datos->direccion)}}" required>
                                        <span id="error_direccion" class="error">Debe ingresar una dirección</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="direccion">Teléfono</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="999999999" value="{{old('telefono',$datos->telefono)}}" required>
                                        <span id="error_telefono" class="error">Debe ingresar un teléfono</span>
                                        <span id="error_telefono_val" class="error">Debe ingresar un teléfono válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="direccion">Correo Electrónico</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="correo_electronico" name="correo_electronico" placeholder="correo@dominio.com" value="{{old('correo_electronico',$datos->correo_electronico)}}" required>
                                        <span id="error_correo" class="error">Debe ingresar un correo electrónico válido</span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="direccion">Sitio Web</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="sitio_web" name="sitio_web" placeholder="www.sitio.cl" value="{{old('sitio_web',$datos->sitio_web)}}" required>
                                        <span id="error_sitio" class="error">Debe ingresar un sitio web</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Logo</label>
                                        <input type="file" class="form-control" id="logo" name="logo" value="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group pt-4">
                                        <a href="{{$datos->ruta}}" target="_blank"><img src="{{$datos->ruta}}" width="30%"></a>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Banco</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_banco" name="id_banco" required style="width:100%">
                                            <option value="">Seleccione</option>
                                            @foreach($bancos as $b)
                                            <option value="{{$b->id}}" @if($b->id == $datos->id_banco) selected @endif>{{$b->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_banco" class="error">Debe seleccionar un banco</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="region">Tipo Cuenta</label> <label class="obligatorio">*</label>
                                        <select class="form-control select2" id="id_tipo_cuenta" name="id_tipo_cuenta" style="width:100%" required>
                                            <option value="">Seleccione</option>
                                            @foreach($tipos_cuenta as $t)
                                            <option value="{{$t->id}}" @if($t->id == $datos->id_tipo_cuenta) selected @endif >{{$t->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_tipo" class="error">Debe seleccionar un tipo</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="region">Número Cuenta</label> <label class="obligatorio">*</label>
                                        <input type="text" class="form-control" id="numero_cuenta" name="numero_cuenta" placeholder="88888888" value="{{old('numero_cuenta',$datos->numero_cuenta)}}" required>
                                        <span id="error_cuenta" class="error">Debe ingresar un número de cuenta</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="button" onclick="validar_empresa()" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                            <a href="{{route('empresas.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                        </div>
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