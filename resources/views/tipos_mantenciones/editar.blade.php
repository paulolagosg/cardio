@extends('adminlte::page')

@section('title', 'Tipos Mantenciones - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Tipos Mantenciones</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/tipos_mantenciones/agregar" urlGuardar="/tipos_mantenciones/actualizar" urlCancelar="/tipos_mantenciones/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop