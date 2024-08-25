@extends('adminlte::page')

@section('title', 'Tipos Productos - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Tipos Productos</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/tipos_productos/agregar" urlGuardar="/tipos_productos/actualizar" urlCancelar="/tipos_productos/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop