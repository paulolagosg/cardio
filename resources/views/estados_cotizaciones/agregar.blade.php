@extends('adminlte::page')

@section('title', 'Estados Cotizaciones - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Estados Cotizaciones</span> <span> Agregar</span>
@stop

@section('content')
<x-agregar-parametros titulo="" urlCancelar="/estados_cotizaciones/lista" urlAgregar="/estados_cotizaciones/crear" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

@stop

@section('js')


@stop