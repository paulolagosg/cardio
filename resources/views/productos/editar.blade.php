@extends('adminlte::page')

@section('title', 'Productos - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Productos</span> <span> Modificar</span>
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

                <div class="card">

                    <form method="post" enctype="multipart/form-data" action="{{route('productos.actualizar')}}">
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
                                <label for="nombre">Descripción</label>
                                <textarea class="form-control" name="descripcion" id="descripcion">{{old('nombre',$datos->descripcion)}}</textarea>
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
                            <div class="form-group">
                                <label for="nombre">Precio</label>
                                <input class="form-control" type="number" name="precio" id="precio" value="{{old('precio',$datos->precio)}}" required />
                            </div>
                            <div class="form-group">
                                <label for="sku">SKU</label>
                                <input class="form-control" type="text" name="sku" id="sku" value="{{old('sku',$datos->sku)}}" />
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Foto del producto</label>
                                        <small>Formatos permitidos jpg, jpeg, png</small>
                                        <input class="form-control" type="file" name="foto" id="foto" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <a href="{{$datos->ruta}}" target="_blank"><img src="{{$datos->ruta}}" width="10%"></a>
                                    </div>
                                </div>
                            </div>


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