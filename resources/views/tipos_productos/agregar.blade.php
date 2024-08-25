@extends('adminlte::page')

@section('title', 'Tipos Productos - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Tipos Productos</span> <span> Agregar</span>
@stop

@section('content')
<x-agregar-parametros titulo="" urlCancelar="/tipos_productos/lista" urlAgregar="/tipos_productos/crear" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

@stop

@section('js')


@stop