@extends('adminlte::page')

@section('title', 'Versiones')

@section('content_header')
<span class="text-xl text-bold text-dark">Versiones</span> <span> Modificar</span>
@stop
@section('content')
<div class="card card-dark">
    <div class="card-body">
        <div class="row">
            @if (session()->has('error'))
            <div class="alert alert-danger" style="display: block;" id="msgerror">
                {{ session('error') }}
            </div>
            @endif
            @if (session()->has('message'))
            <div class="alert alert-success" style="display: block;" id="msgsuccess">
                {{ session('message') }}
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <form id="fVersiones" method="POST" action="{{route('versiones.actualizar')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre Versión</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{$datos->nombre}}" required />
                                        <span id="error_nombre" class="error">Debe ingresar un nombre</span>
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{$datos->id}}" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Empresa</label>
                                        <select class="form-control select2" id="id_cliente" name="id_cliente" style="width:100%">
                                            <option value="">Seleccione un cliente</option>
                                            @foreach($clientes as $c)
                                            <option value="{{$c->id}}" @if($datos->id_cliente == $c->id) selected @endif>{{$c->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_cliente" class="error">Debe seleccionar un cliente</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Curso</label>
                                        <select class="form-control select2" id="id_curso" name="id_curso" style="width:100%">
                                            <option value="">Seleccione un curso</option>
                                            @foreach($cursos as $curso)
                                            <option value="{{$curso->id}}" @if($datos->id_curso == $curso->id) selected @endif>{{$curso->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_curso" class="error">Debe seleccionar un curso</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Modalidad</label>
                                        <select class="form-control select2" id="id_modalidad" name="id_modalidad" style="width:100%">
                                            <option value="">Seleccione una modalidad</option>
                                            @foreach($modalidades as $m)
                                            <option value="{{$m->id}}" @if($datos->id_modalidad == $m->id) selected @endif>{{$m->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_modalidad" class="error">Debe seleccionar una modalidad</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="correo">Fecha Curso</label>
                                        <input type="date" class="form-control" id="fecha_curso" name="fecha_curso" value="{{$datos->fecha_version}}" required />
                                        <span id="error_fecha" class="error">Debe ingresar una fecha válida</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="correo">Instructor</label>
                                        <select class="form-control select2" id="id_usuario_instructor" name="id_usuario_instructor" style="width:100%">
                                            <option value="">Seleccione un Instructor</option>
                                            @foreach($usuarios as $m)
                                            <option value="{{$m->id}}" @if($datos->id_usuario_instructor == $m->id) selected @endif>{{$m->name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_instructor" class="error">Debe seleccionar un instructor</span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="correo">Horas</label>
                                        <input type="number" class="form-control" id="horas" name="horas" value="{{$datos->horas}}" required />
                                        <span id="error_horas" class="error">Debe ingresar horas válidas</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="correo">Ciudad</label>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{$datos->ciudad}}" required />
                                        <span id="error_horas" class="error">Debe ingresar una ciudad válida</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="correo">Firmante</label>
                                        <select class="form-control select2" id="id_usuario_firmante" name="id_usuario_firmante" style="width:100%">
                                            <option value="">Seleccione un Instructor</option>
                                            @foreach($usuarios as $m)
                                            <option value="{{$m->id}}" @if($datos->id_usuario_firmante == $m->id) selected @endif>{{$m->name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_firmante" class="error">Debe seleccionar un firmante</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="correo">Empresa</label>
                                        <select class="form-control select2" id="id_empresa" name="id_empresa" style="width:100%">
                                            <option value="">Seleccione una Empresa</option>
                                            @foreach($empresas as $m)
                                            <option value="{{$m->id}}" @if($datos->id_empresa == $m->id) selected @endif>{{$m->razon_social}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_firmante" class="error">Debe seleccionar un firmante</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="correo">Foto de Firma</label>
                                        <input type="file" class="form-control" id="firma" name="firma" />
                                        <small>Formatos permitidos jpg, jpeg, png</small>
                                    </div>
                                </div>
                                @if(isset($datos->ruta) && $datos->ruta != ' ')
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <img src="{{$datos->ruta}}" style="width:200px" />
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-dark">
                                        <div class="card-header">
                                            <h3 class="card-title">Contraparte Empresa</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="sw">Rut</label>
                                                        <input type="text" class="form-control" id="rut" name="rut" placeholder="99999999-9" value="{{$datos->rut}}">
                                                        <span id="error_rut" class="error">RUT no válido</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="sw">Nombre</label>
                                                        <input type="text" class="form-control" id="contraparte" name="contraparte" placeholder="Nombre" value="{{$datos->contraparte}}">
                                                        <span id="error_contraparte" class="error">Debe ingresar un nombre</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="telefono">Teléfono</label>
                                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="888888888" value="{{$datos->telefono}}">
                                                        <span id="error_telefono" class="error">Teléfono no válido</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="email">Correo electrónico</label>
                                                        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="{{$datos->correo_electronico}}" placeholder="Correo electrónico">
                                                        <span id="error_correo" class="error">Correo electrónico no válido</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" onclick="validar_version()" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                                    <a href="{{route('versiones.index')}}" class="btn bg-dark"><i class="fa fa-ban"></i> Cancelar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">


<style>
    .page-item.active .page-link {
        background-color: #811916 !important;
        border: 1px solid #dee2e6;
        color: #fff !important;
    }

    .page-link {
        color: #811916 !important;
    }

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
@stop

@section('js')
<script src="/vendor/adminlte/dist/js/functions.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.alert-success').fadeIn().delay(3000).fadeOut();
        $('.alert-danger').fadeIn().delay(3000).fadeOut();

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