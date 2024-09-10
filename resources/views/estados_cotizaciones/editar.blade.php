@extends('adminlte::page')

@section('title', 'Estados Cotizaciones - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Estados Cotizaciones</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/estados_cotizaciones/agregar" urlGuardar="/estados_cotizaciones/actualizar" urlCancelar="/estados_cotizaciones/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop