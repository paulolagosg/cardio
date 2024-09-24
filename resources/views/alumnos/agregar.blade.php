@extends('adminlte::page')

@section('title', 'Alumnos - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Alumnos</span> <span> Agregar</span>
@stop

@section('content')
<div class="card  card-dark">
    <div class="card-header">
    </div>
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
                    <form method="post" id="fAlumnos" action="{{route('alumnos.crear')}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nombre">Versión Curso</label>
                                        <select id="id_version" name="id_version" class="form-control select2" style="width:100%">
                                            <option value="">Seleccione</option>
                                            @foreach($versiones as $version)
                                            <option value="{{$version->id}}">{{$version->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span id="error_curso" class="error">Debe seleccionar un curso</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">RUT</label>
                                        <input type="text" class="form-control" id="rut" name="rut" placeholder="11111111-1" value="{{old('rut')}}" required>
                                        <span id="error_rut" class="error">Debe ingresar un RUT válido</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{old('nombre')}}" required>
                                        <span id="error_nombre" class="error">Debe ingresar un nombre</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Correo Electrónico</label>
                                        <input type="text" class="form-control" id="correo_electronico" name="correo_electronico" placeholder="correo@dominio.cl" value="{{old('correo_electronico')}}" required>
                                        <span id="error_correo" class="error">Debe ingresar un correo válido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Nota</label>
                                        <input type="text" class="form-control" id="nota" name="nota" placeholder="6.0" value="{{old('nota')}}" required>
                                        <span id="error_nota" class="error">Debe ingresar una nota válida</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Asistencia</label>
                                        <input type="text" class="form-control" id="asistencia" name="asistencia" placeholder="85" value="{{old('nombasistenciare')}}" required>
                                        <span id="error_asistencia" class="error">Debe ingresar una asistencia válida</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="button" onclick="validar_alumno()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                                <a href="{{route('alumnos.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                            </div>
                    </form>
                </div>
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
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')
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