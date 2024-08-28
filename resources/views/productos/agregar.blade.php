@extends('adminlte::page')

@section('title', 'Productos - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Productos</span> <span> Agregar</span>
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

                    <form method="post" action="{{route('productos.crear')}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{old('nombre')}}" required>
                            </div>
                            <div class="form-group">
                                <!-- <label for="nombre">Tipo&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="text-dark" onclick="agregar_tipo_producto('Agregar Tipo',1)"><i class="fa fa-plus-circle"></i></a></label> -->
                                <label for="nombre">Tipo</label>
                                <select class="form-control select2" id="id_tipo" name="id_tipo" required>
                                    <option value="">Seleccione</option>
                                    @foreach($tipos as $t)
                                    <option value="{{$t->id}}">{{$t->nombre}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="nombre">Marca</label>
                                <select class="form-control select2" id="id_marca" name="id_marca" onchange="obtener_modelos(this.value)" required>
                                    <option value="">Seleccione</option>
                                    @foreach($marcas as $marca)
                                    <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Modelo</label>
                                <select class="form-control select2" id="id_modelo" name="id_modelo" required>
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                                <a href="{{route('productos.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal agregar parametros -->
<div class="modal fade" id="modal-agregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="fModal" action="">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_modal">Agregar</h4>
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
                                <input type="text" class="form-control" id="lote" name="lote" value="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-right">
                    <button type="button" onclick="guardar_adicional()" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                    <button type="button" class="btn text-white" style="background-color: #87161b" data-dismiss="modal"><i class="fa fa-ban"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

@stop

@section('js')
<script src="/vendor/adminlte/dist/js/functions.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@stop