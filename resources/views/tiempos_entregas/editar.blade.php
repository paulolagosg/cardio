@extends('adminlte::page')

@section('title', 'Tiempos Entrega - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Tiempos Entrega</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/tiempos_entregas/agregar" urlGuardar="/tiempos_entregas/actualizar" urlCancelar="/tiempos_entregas/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop