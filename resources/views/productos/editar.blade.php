@extends('adminlte::page')

@section('title', 'Productos - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Productos</span> <span> Modificar</span>
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

                    <form method="post" action="{{route('productos.actualizar')}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{old('nombre',$datos->nombre)}}" required>
                                <input type="hidden" id="id" name="id" value="{{$datos->id}}" />
                                <input type="hidden" id="id_modelo_bd" name="id_modelo_bd" value="{{$datos->id_modelo}}" />
                                <input type="hidden" id="id_marca_bd" name="id_marca_bd" value="{{$datos->id_marca}}" />
                            </div>
                            <div class="form-group">
                                <label for="nombre">Tipo</label>
                                <select class="form-control select2" id="id_tipo" name="id_tipo" required>
                                    <option value="">Seleccione</option>
                                    @foreach($tipos as $t)
                                    <option value="{{$t->id}}" @if($t->id == $datos->id_tipo_producto) selected @endif >{{$t->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Marca</label>
                                <select class="form-control select2" id="id_marca" name="id_marca" onchange="obtener_modelos(this.value)" required>
                                    <option value="">Seleccione</option>
                                    @foreach($marcas as $marca)
                                    <option value="{{$marca->id}}" @if($marca->id == $datos->id_marca) selected @endif >{{$marca->nombre}}</option>
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
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                                <a href="{{route('productos.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')
<script>
    $(document).ready(function() {
        obtener_modelos($('#id_marca_bd').val());
        setTimeout(function() {
            $('#id_modelo').val($('#id_modelo_bd').val()); // Select the option with a value of '1'
            $('#id_modelo').trigger('change');
        }, 2000);
    });
</script>
@stop