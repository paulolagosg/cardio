@extends('adminlte::page')

@section('title', 'Tipos Transportes - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Tipos Transportes</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/tipos_transportes/agregar" urlGuardar="/tipos_transportes/actualizar" urlCancelar="/tipos_transportes/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop