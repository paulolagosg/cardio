@extends('adminlte::page')

@section('title', 'Estados Mantenciones - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Estados Mantenciones</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/estados_mantenciones/agregar" urlGuardar="/estados_mantenciones/actualizar" urlCancelar="/estados_mantenciones/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop