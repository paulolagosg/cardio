@extends('adminlte::page')

@section('title', 'Modelos - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Modelos</span> <span> Agregar</span>
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

                    <form method="post" action="{{route('modelos.crear')}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Marca</label>
                                <select class="form-control select2" id="id_marca" name="id_marca" required>
                                    <option value="">Seleccione</option>
                                    @foreach($marcas as $marca)
                                    <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Modelo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{old('nombre')}}" required>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Agregar</button>
                                <a href="{{route('modelos.index')}}" class="btn text-white" style="background-color: #87161b"><i class="fa fa-ban"></i> Cancelar</a>
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
        $('.select2').select2();
    });
</script>
@stop